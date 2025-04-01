<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FacebookEmbed extends Component
{
  /**
   * Create a new component instance.
   *
   * @return void
   */
  public $url;
  public $width;
  public $height;

  public function __construct($url, $width = 500, $height = 281)
  {
    $this->url = $url;
    $this->width = $width;
    $this->height = $height;
  }

  /**
   * Get the view / contents that represent the component.
   *
   * @return \Illuminate\View\View|string
   */
  public function render()
  {
    return view('components.facebook-embed');
  }
}
