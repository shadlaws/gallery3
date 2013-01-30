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
class Image_GraphicsMagick extends Image_GraphicsMagick_Driver {
  /**
   * We've modified the process function to add interlace and strip.  We handle it similar to
   * quality, in that they are added to the actions array but removed before running execute.
   *
   * Modified code is denoted by "BEGIN: modifications to include interlace and strip" before
   * and "END: modifications to include interlace and strip" after the code.
   *
   * For GraphicsMagick, interlace *must* be done in the last "gm" execution or else it may not
   * be preserved in future steps.  Strip could come earlier, but is included at the same place
   * for efficiency of code and operation.
   */
  public function process($image, $actions, $dir, $file, $render = FALSE, $background = NULL)
  {
    // Need to implement $background support
    if ($background !== NULL)
      throw new Kohana_Exception('The GraphicsMagick driver does not support setting a background color');

    // We only need the filename
    $image = $image['file'];

    // Unique temporary filename
    $this->tmp_image = $dir.'k2img--'.sha1(time().$dir.$file).substr($file, strrpos($file, '.'));

    // Copy the image to the temporary file
    copy($image, $this->tmp_image);

    // Quality change is done last
    $quality = (int) arr::remove('quality', $actions);

    // Use 95 for the default quality
    empty($quality) and $quality = 95;

    // BEGIN: modifications to include interlace and strip (these lines added)
    if (!is_null($interlace = arr::remove('interlace', $actions))) {
      $interlace = $interlace ? '-interlace Plane ' : '-interlace None ';
    } else {
      $interlace = '';
    }
    $strip = !empty(arr::remove('strip'), $actions) ? '-strip ' : '';
    // END: modifications to include interlace and strip

    // All calls to these will need to be escaped, so do it now
    $this->cmd_image = escapeshellarg($this->tmp_image);
    $this->new_image = ($render)? $this->cmd_image : escapeshellarg($dir.$file);

    if ($status = $this->execute($actions))
    {
      // Use convert to change the image into its final version. This is
      // done to allow the file type to change correctly, and to handle
      // the quality conversion in the most effective way possible.
      // BEGIN: modifications to include interlace and strip (commented line is modified)
      //if ($error = exec(escapeshellcmd($this->dir.'gm'.$this->ext.' convert').' -quality '.$quality.'% '.$this->cmd_image.' '.$this->new_image))
      if ($error = exec(escapeshellcmd($this->dir.'gm'.$this->ext.' convert').' -quality '.$quality.'% '.$interlace.$strip.$this->cmd_image.' '.$this->new_image))
      // END: modifications to include interlace and strip
      {
        $this->errors[] = $error;
      }
      else
      {
        // Output the image directly to the browser
        if ($render !== FALSE)
        {
          $contents = file_get_contents($this->tmp_image);
          switch (substr($file, strrpos($file, '.') + 1))
          {
            case 'jpg':
            case 'jpeg':
              header('Content-Type: image/jpeg');
            break;
            case 'gif':
              header('Content-Type: image/gif');
            break;
            case 'png':
              header('Content-Type: image/png');
            break;
           }
          echo $contents;
        }
      }
    }

    // Remove the temporary image
    unlink($this->tmp_image);
    $this->tmp_image = '';

    return $status;
  }
} // End Image GraphicsMagick Driver