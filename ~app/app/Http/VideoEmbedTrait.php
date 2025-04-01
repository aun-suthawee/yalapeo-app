<?php

namespace App\Http;


use Embed\Embed;

trait VideoEmbedTrait
{

  public function Embed($url)
  {
    $embed = new Embed();
    $info = $embed->get($url);
    if ($info->providerName == 'YouTube') {
      $url = $info->code;
      return \View::make('components.youtube-embed', compact('url'));
    } elseif ($info->providerName == 'Facebook') {
      $url = $this->videoUrl($info);
      if (!empty($url)) {
        return \View::make('components.facebook-embed', compact('url'));
      }
    }

    return $info->code;
  }

  private function videoUrl($info): ?string
  {
    $url = $info->url;
    preg_match("#(\d+)/$#", $url, $match);

    if (array_key_exists(1, $match)) {
      return $url;
    }

    return null;
  }
}
