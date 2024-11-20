# WP Acorn Command: Media Sync for WordPress
# (Sage Roots.io)

The Media Sync Command is a customizable WP Acorn command designed for WordPress projects using the Sage Roots.io framework. It streamlines the process of synchronizing media files from the uploads directory directly into the WordPress Media Library. With an interactive CLI-based approach, developers gain precise control over media synchronization, avoiding the inefficiencies of manual imports.

## Why Use the Media Sync Command?
By default, WordPress handles media uploads and synchronization through its admin interface, which can be time-consuming and lacks flexibility for developers managing files directly. The Media Sync Command addresses these issues by offering:
- Direct Media Synchronization:
  Add images, PDFs, videos, or other media directly to the WordPress Media Library from the filesystem.
- Customizable Workflow:
- Scan the entire uploads folder or a specific subdirectory, with options for dry-run simulations.
- Developer-Friendly Integration:
  Built for WP Acorn, it integrates seamlessly into Sage projects.
- Interactive CLI:
  Provides options for folder selection and file handling, ensuring an efficient workflow.

## Why Use the Media Sync Command?
By default, WordPress handles media uploads and synchronization through its admin interface, which can be time-consuming and lacks flexibility for developers managing files directly. The Media Sync Command addresses these issues by offering:
- Direct Media Synchronization:
  Add images, PDFs, videos, or other media directly to the WordPress Media Library from the filesystem.
- Customizable Workflow:
  Scan the entire uploads folder or a specific subdirectory, with options for dry-run simulations.
- Developer-Friendly Integration:
  Built for WP Acorn, it integrates seamlessly into Sage projects.
- Interactive CLI:
  Provides options for folder selection and file handling, ensuring an efficient workflow.

 ## Key Features
- Interactive Options:
  Select whether to scan the entire uploads folder or a specific subfolder.
  Enable or disable a “dry-run” mode to simulate synchronization without making changes.
-	Supports Multiple File Formats:
  andles images (jpg, png, gif, webp), videos (mp4, mov, avi), PDFs, and SVGs.
- Database Sync:
  utomatically adds new files to the WordPress Media Library, skipping already-synced files.
  Error Handling and Logs:
-	Displays skipped files, successfully imported media, and any errors encountered.
  Optimized for Developers:
- Integrates natively into WP Acorn, providing a Laravel-like command interface.

## Advantages
- Efficient Media Management:
  Eliminate the need for manual uploads via the WordPress admin interface.
- Customizable Scans:
  Choose specific directories or file types to sync, reducing unnecessary processing.
- Integration with WordPress:
  Automatically updates the WordPress database with proper attachment metadata.
- Flexible CLI Options:
  Run simulations, specify folders, and monitor progress directly from the command line.

  Installation and Setup

## Prerequisites
- WordPress project using Sage Roots.io with WP Acorn installed.
-	PHP version 7.4 or higher.

## Steps
- Place the file at app/Console/Commands/MediaSyncCommand.php

## Usage

Run the Command:

- Synchronize all media files: wp acorn media:sync
- Dry-Run (No changes): wp acorn media:sync --dry-run
- Scan a specific subfolder: wp acorn media:sync
  Choose “Specify a subfolder” and enter the folder path (e.g., 2024/11).

