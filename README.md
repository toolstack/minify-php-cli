# Minify PHP CLI

This script provides a CLI interface to Minify JavaScript/CSS files.  It uses [@mcclay's](https://github.com/mrclay) PHP base [Minify code](https://github.com/mrclay/jsmin-php) to do the bulk of the work.

## Usage

`php cli.php <script> <script> <dir>`

`<script>` can be any JavaScript file that ends in '.js' or '.css' (it is double checked before being added to the file queue).
`<dir>` can be any directory, the CLI script will look inside and find any '.js' or '.css' files and add them to the queue.

You can have as many `<script>` and `<dir>`'s as you like (until you run out of memory of course).

The script will minify each file, creating a '.min.js' or '.min.css' version of it.

If a minified file already exists, the last modified time of the unminified file and the minified file will be compared, if the minified file is newer, it will be skipped.

Output is a simple summary of how many files were found, processed and skipped.

The script will run on Windows or *nix (and anywhere else that PHP runs more than likely).