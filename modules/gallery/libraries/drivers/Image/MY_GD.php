<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2013 Bharat Mediratta
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street - Fifth Floor, Boston, MA  02110-1301, USA.
 */
class Image_GD extends Image_GD_Driver {
  /**
   * We've modified the process function to add interlace and strip.  We handle it similar to
   * quality, in that they are added to the actions array but removed before running execute.
   *
   * Modified code is denoted by "BEGIN: modifications to include interlace and strip" before
   * and "END: modifications to include interlace and strip" after the code.
   *
   * For GD, the interlace can be performed at any time between creation and saving.  For
   * simplicity, we perform it just before running execute.  We also ensure that strip is removed
   * from the actions array (and do nothing with its value) before running execute.
   */
  public function process($image, $actions, $dir, $file, $render = FALSE, $background = NULL)
  {
    // Set the "create" function
    switch ($image['type'])
    {
      case IMAGETYPE_JPEG:
        $create = 'imagecreatefromjpeg';
      break;
      case IMAGETYPE_GIF:
        $create = 'imagecreatefromgif';
      break;
      case IMAGETYPE_PNG:
        $create = 'imagecreatefrompng';
      break;
    }

    // Set the "save" function
    switch (strtolower(substr(strrchr($file, '.'), 1)))
    {
      case 'jpg':
      case 'jpeg':
        $save = 'imagejpeg';
      break;
      case 'gif':
        $save = 'imagegif';
      break;
      case 'png':
        $save = 'imagepng';
      break;
    }

    // Make sure the image type is supported for import
    if (empty($create) OR ! function_exists($create))
      throw new Kohana_Exception('The specified image, :type:, is not an allowed image type.', array(':type:' => $image['file']));

    // Make sure the image type is supported for saving
    if (empty($save) OR ! function_exists($save))
      throw new Kohana_Exception('The specified image, :type:, is not an allowed image type.', array(':type:' => $dir.$file));

    // Load the image
    $this->image = $image;

    // Create the GD image resource
    $this->tmp_image = $create($image['file']);

    // Get the quality setting from the actions
    $quality = arr::remove('quality', $actions);

    // BEGIN: modifications to include interlace and strip (these lines added)
    if (!is_null($interlace = arr::remove('interlace', $actions)) &&
        function_exists("imageinterlace") && !imageinterlace($this->tmp_image, $interlace)) {
      // interlace specified, function exists, but failed to execute function
      return false;
    }
    arr::remove('strip', $actions); // this action does not exist for GD
    // END: modifications to include interlace and strip

    if ($status = $this->execute($actions))
    {
      // Prevent the alpha from being lost
      imagealphablending($this->tmp_image, TRUE);
      imagesavealpha($this->tmp_image, TRUE);

      switch ($save)
      {
        case 'imagejpeg':
          // Default the quality to 95
          ($quality === NULL) and $quality = 95;
        break;
        case 'imagegif':
          // Remove the quality setting, GIF doesn't use it
          unset($quality);
        break;
        case 'imagepng':
          // Always use a compression level of 9 for PNGs. This does not
          // affect quality, it only increases the level of compression!
          $quality = 9;
        break;
      }

      if ($render === FALSE)
      {
        // Set the status to the save return value, saving with the quality requested
        $status = isset($quality) ? $save($this->tmp_image, $dir.$file, $quality) : $save($this->tmp_image, $dir.$file);
      }
      else
      {
        // Output the image directly to the browser
        switch ($save)
        {
          case 'imagejpeg':
            header('Content-Type: image/jpeg');
          break;
          case 'imagegif':
            header('Content-Type: image/gif');
          break;
          case 'imagepng':
            header('Content-Type: image/png');
          break;
        }

        $status = isset($quality) ? $save($this->tmp_image, NULL, $quality) : $save($this->tmp_image);
      }

      // Destroy the temporary image
      imagedestroy($this->tmp_image);
    }

    return $status;
  }
}