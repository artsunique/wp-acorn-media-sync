<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MediaSyncCommand extends Command
{
    protected $signature = 'media:sync';
    protected $description = 'Synchronize media files with customizable options.';

    public function handle()
    {
        $this->info("Welcome to the Media Sync Tool!");

        $folder = $this->choice(
            'Choose the folder to scan:',
            ['Entire uploads folder', 'Specify a subfolder'],
            0
        );

        $dryRun = $this->confirm('Would you like to run a dry-run (no changes made)?', false);
        $scanSubfolder = null;

        if ($folder === 'Specify a subfolder') {
            $scanSubfolder = $this->ask('Enter the subfolder path relative to uploads (e.g., "2024/11")');
        }

        $baseDir = wp_get_upload_dir()['basedir'];
        $scanDir = $scanSubfolder ? $baseDir . '/' . $scanSubfolder : $baseDir;

        if (!is_dir($scanDir)) {
            $this->error("The specified folder does not exist: $scanDir");
            return Command::FAILURE;
        }

        $this->info($dryRun ? 'Running in dry-run mode. No changes will be made.' : 'Starting media sync...');
        $this->info("Scanning directory: $scanDir");

        $files = $this->scanFolderForMedia($scanDir);
        if (empty($files)) {
            $this->info('No media files found to sync.');
            return Command::SUCCESS;
        }

        $count = 0;

        foreach ($files as $file) {
            if ($this->mediaFileExists($file)) {
                $this->line("Skipped (already exists): $file");
                continue;
            }

            if ($dryRun) {
                $this->info("[Dry-Run] Would import: $file");
                continue;
            }

            try {
                $attachmentId = $this->importMediaFile($file);
                if ($attachmentId) {
                    $this->info("Imported: $file");
                    $count++;
                } else {
                    $this->warn("Failed to import: $file");
                }
            } catch (\Exception $e) {
                $this->error("Error importing $file: " . $e->getMessage());
            }
        }

        $this->info("$count new files added to the media library.");
        return Command::SUCCESS;
    }

    private function scanFolderForMedia($folder)
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'svg', 'mp4', 'mov', 'avi'];
        $files = [];

        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($folder));
        foreach ($iterator as $file) {
            if ($file->isFile() && in_array(strtolower($file->getExtension()), $allowedExtensions)) {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }

    private function mediaFileExists($file)
    {
        global $wpdb;

        $uploadDir = wp_get_upload_dir();
        $fileUrl = str_replace($uploadDir['basedir'], $uploadDir['baseurl'], $file);

        $query = $wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = 'attachment' AND guid = %s",
            $fileUrl
        );

        return $wpdb->get_var($query) > 0;
    }

    private function importMediaFile($file)
    {
        $filetype = wp_check_filetype($file);
        $uploadDir = wp_get_upload_dir();

        $attachment = [
            'guid'           => $uploadDir['baseurl'] . '/' . _wp_relative_upload_path($file),
            'post_mime_type' => $filetype['type'],
            'post_title'     => sanitize_file_name(pathinfo($file, PATHINFO_FILENAME)),
            'post_content'   => '',
            'post_status'    => 'inherit',
        ];

        $attachmentId = wp_insert_attachment($attachment, $file);

        if (!is_wp_error($attachmentId)) {
            require_once ABSPATH . 'wp-admin/includes/image.php';
            $metadata = wp_generate_attachment_metadata($attachmentId, $file);
            wp_update_attachment_metadata($attachmentId, $metadata);
        }

        return $attachmentId;
    }
}
