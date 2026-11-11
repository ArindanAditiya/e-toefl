<?php
$linkName = 'E:\xampp81\htdocs\toefl\public\storage';

$target = 'E:\xampp81\htdocs\toefl\storage\app\public';

if(@symlink($target, $linkName) === false){
  echo "Start readlink<br>";
  if(@($rl = readlink($target))===false){ //not a cache issue as stat on a non-existing file failed, something else must be wrong...
    echo 'readlink : false';
    return false;
  }

  //clear the stat cache for the actual target, strangely enough this works even-though this is the target and not the symlink to be created (*)
  echo "start clearstatcache";
  clearstatcache(true, $rl);

  echo "try again symlink";
  if(@symlink($target, $linkName) ===false) echo 'symlink : false'; //can't create the symlink
}

echo "Success use symlink";
