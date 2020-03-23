# DrupalFileSearch

Drupal Text File Search

This utility will open all files that have been uploaded into Drupal using a 'File' field type, and search through them for the given search criteria.

I've had issues reading any file with a .sql extension, and there may be others. If the files are renamed to .txt they'll work fine.

To use this utility:

Create a new content, either webform (if you have it) or Basic Content
In the body of the content, copy/paste the contents of the file WebFormText.txt
Select the content type 'Full HTML'
In the body header, ensure 'Source' is select
Save
Copy the script folder (or just the contents if it already exists) to the /htdocs folder (at the same level as the Drupal folder).
This project uses html, css, JavaScript, jquery, php and sql.
