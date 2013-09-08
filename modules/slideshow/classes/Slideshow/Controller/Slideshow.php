<?php defined("SYSPATH") or die("No direct script access.");
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
class Slideshow_Controller_Slideshow extends Controller {
  public function action_js() {
    $id = $this->request->arg(0, "digit");
    $item = ORM::factory("Item", $id);
    Access::required("view", $item);

    $entities = array();
    foreach ($item->descendants->viewable()->find_all() as $child) {
      if (!$child->is_album()) {
        $entities[] = $this->_item_data($child);
      }
    }

    $view = new View("slideshow/slideshow.js");
    $view->items = json_encode($entities, true);
    $view->fs_png = URL::abs_file("modules/slideshow/assets/ico-fullscreen.png");
    $view->ss_theme = Module::get_var("slideshow", "theme", "classic");

    $this->response->headers("Content-Type", "application/x-javascript; charset=UTF-8");
    $this->response->body($view);
  }

  public function action_iframe() {
    $id = $this->request->arg(0, "digit");
    $item = ORM::factory("Item", $id);
    Access::required("view", $item);

    $entities = array();
    foreach ($item->descendants->viewable()->find_all() as $child) {
      if (!$child->is_album()) {
        $entities[] = $this->_item_data($child);
      }
    }

    $view = new View("slideshow/iframe.html");
    $view->item = json_encode($this->_item_data($item), true);
    $view->code = URL::abs_file("lib/flowplayer-flash/"); // @TODO: fix this!
    $view->ss_theme = Module::get_var("slideshow", "theme", "classic");

    $this->response->body($view);
  }

  private function _item_data($item) {
    // @TODO: refactor this and make it work for non-public items.  Maybe it should use REST?
    $data = $item->as_array();

    if (!$item->is_root()) {
      $data["parent"] = $item->parent->abs_url();
    }
    unset($data["parent_id"]);

    $data["web_url"] = $item->abs_url();

    if (Access::user_can(Identity::guest(), "view", $item)) {
      $data["thumb_url_public"] = $item->thumb_url(true);
      if ($item->is_photo()) {
        $data["resize_url_public"] = $item->resize_url(true);
      } else if ($item->is_movie()) {
        $data["iframe_url_public"] = URL::site("slideshow/iframe/{$item->id}");
      }
      if (Access::user_can(Identity::guest(), "view_full", $item)) {
        $data["file_url_public"] = $item->file_url(true);
      }
    }

    // Elide some internal-only data that is not useful for the slideshow.
    foreach (array("relative_path_cache", "relative_url_cache", "left_ptr", "right_ptr",
                   "thumb_dirty", "resize_dirty", "weight", "level", "album_cover_item_id",
                   "captured", "owner_id", "rand_key", "sort_column", "sort_order", "updated",
                   "view_1", "view_2", "view_count", "slug") as $key) {
      unset($data[$key]);
    }
    return $data;
  }
}
