<?php
  Configure::write ('AMU.directory', 'files');
  Configure::write ('AMU.sub_directory', 'uploads');
  Configure::write ('AMU.temp_directory', 'tmp');
  Configure::write ('AMU.items_per_folder', '1000');
  Configure::write ('AMU.filesizeMB', '500');
  Configure::write ('AMU.limit_show_name', '20');
  Configure::write ('AMU.limit_show_desc', '30');
  Configure::write ('AMU.allowed_extensions', 'gif,png,jpg,jpeg,xls,xlsx,pdf');
  Configure::write ('AMU.image_extensions', 'png,jpg,jpeg,gif');
  Configure::write ('AMU.limit_paging', '20');
  Configure::write ('AMU.limit_paging_choose_file', '50');
  Configure::write ('AMU.thumbnail_directory', 'thumbnails');
  Configure::write ('AMU.thumbnail_max_width', '0'); //do not care with
  Configure::write ('AMU.thumbnail_max_height', '100'); //100 pixels for height

  define('SF_FILE_MODULE_PHOTO', 'PHOTO');
  define('SF_FILE_MODULE_CRITERIA_CATEGORY_USER_FILE', 'CRITERIA_CATEGORY_USER_FILE');
  define('SF_FILE_MODULE_CRITERIA_RESULT_USER_FILE', 'CRITERIA_RESULT_USER_FILE');
  define('SF_FILE_MODULE_FILE_USER_NOTE', 'FILE_USER_NOTE');
define('SF_FILE_MODULE_PHOTO_NOTE', 'PHOTO_NOTE');
