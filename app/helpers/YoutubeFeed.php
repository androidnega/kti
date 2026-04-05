<?php

/**
 * Fetches public videos from a YouTube channel via RSS (no API key),
 * or via YouTube Data API v3 when YOUTUBE_API_KEY is set (more videos + pagination).
 */
class YoutubeFeed {
    private const RSS_NS_ATOM = 'http://www.w3.org/2005/Atom';
    private const RSS_NS_YT = 'http://www.youtube.com/xml/schemas/2015';
    private const RSS_NS_MEDIA = 'http://search.yahoo.com/mrss/';

    /**
     * Build video rows from YOUTUBE_CURATED_VIDEO_IDS using YouTube oEmbed (no API key).
     *
     * @return array<int, array{video_id:string,title:string,published:string,thumbnail:string,url:string}>
     */
    public static function buildCuratedVideos() {
        if (!defined('YOUTUBE_CURATED_VIDEO_IDS') || !is_array(YOUTUBE_CURATED_VIDEO_IDS)) {
            return [];
        }
        $out = [];
        foreach (YOUTUBE_CURATED_VIDEO_IDS as $rawId) {
            $videoId = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $rawId);
            if ($videoId === '') {
                continue;
            }
            $watchUrl = 'https://www.youtube.com/watch?v=' . rawurlencode($videoId);
            $oembedUrl = 'https://www.youtube.com/oembed?format=json&url=' . rawurlencode($watchUrl);
            $json = self::httpGet($oembedUrl);
            $title = 'KTI on YouTube';
            $thumb = 'https://i.ytimg.com/vi/' . $videoId . '/hqdefault.jpg';
            if ($json !== null) {
                $data = json_decode($json, true);
                if (is_array($data)) {
                    if (!empty($data['title']) && is_string($data['title'])) {
                        $title = $data['title'];
                    }
                    if (!empty($data['thumbnail_url']) && is_string($data['thumbnail_url'])) {
                        $thumb = $data['thumbnail_url'];
                    }
                }
            }
            $out[] = [
                'video_id' => $videoId,
                'title' => $title,
                'published' => '',
                'thumbnail' => $thumb,
                'url' => $watchUrl,
            ];
        }
        return $out;
    }

    /**
     * @return array{0: array<int, array{video_id:string,title:string,published:string,thumbnail:string,url:string}>, 1: ?string} [videos, error message]
     */
    public static function fetchChannelVideos() {
        $channelId = defined('YOUTUBE_CHANNEL_ID') ? YOUTUBE_CHANNEL_ID : '';
        if ($channelId === '') {
            return [[], 'YouTube channel is not configured.'];
        }

        if (defined('YOUTUBE_API_KEY') && YOUTUBE_API_KEY !== '') {
            $out = self::fetchViaApi(YOUTUBE_API_KEY, $channelId);
            if ($out[1] === null && count($out[0]) > 0) {
                return $out;
            }
            // Fall back to RSS if API fails
        }

        return self::fetchViaRss($channelId);
    }

    /**
     * @return array{0: array<int, array<string, string>>, 1: ?string}
     */
    private static function fetchViaRss($channelId) {
        $url = 'https://www.youtube.com/feeds/videos.xml?channel_id=' . rawurlencode($channelId);
        $xml = self::httpGet($url);
        if ($xml === null) {
            return [[], 'Could not load videos from YouTube right now. Please try again later.'];
        }

        libxml_use_internal_errors(true);
        $feed = simplexml_load_string($xml);
        if ($feed === false) {
            return [[], 'Could not read the video feed.'];
        }

        $videos = [];
        foreach ($feed->entry as $entry) {
            $yt = $entry->children(self::RSS_NS_YT);
            $videoId = (string) $yt->videoId;
            if ($videoId === '') {
                continue;
            }

            $thumb = '';
            $mg = $entry->children(self::RSS_NS_MEDIA)->group;
            if ($mg !== null) {
                $th = $mg->children(self::RSS_NS_MEDIA)->thumbnail;
                if ($th !== null) {
                    $attrs = $th->attributes();
                    $thumb = (string) ($attrs['url'] ?? '');
                }
            }

            $videos[] = [
                'video_id' => $videoId,
                'title' => (string) $entry->title,
                'published' => (string) $entry->published,
                'thumbnail' => $thumb !== '' ? $thumb : 'https://i.ytimg.com/vi/' . $videoId . '/hqdefault.jpg',
                'url' => 'https://www.youtube.com/watch?v=' . rawurlencode($videoId),
            ];
        }

        return [$videos, null];
    }

    /**
     * @return array{0: array<int, array<string, string>>, 1: ?string}
     */
    private static function fetchViaApi($apiKey, $channelId) {
        if (strpos($channelId, 'UC') !== 0 || strlen($channelId) < 10) {
            return [[], 'Invalid channel ID for API.'];
        }

        $uploadsPlaylistId = 'UU' . substr($channelId, 2);
        $videos = [];
        $pageToken = '';

        do {
            $q = [
                'part' => 'snippet',
                'playlistId' => $uploadsPlaylistId,
                'maxResults' => '50',
                'key' => $apiKey,
            ];
            if ($pageToken !== '') {
                $q['pageToken'] = $pageToken;
            }
            $url = 'https://www.googleapis.com/youtube/v3/playlistItems?' . http_build_query($q);
            $json = self::httpGet($url);
            if ($json === null) {
                return [[], 'YouTube API request failed.'];
            }
            $data = json_decode($json, true);
            if (!is_array($data)) {
                return [[], 'Invalid API response.'];
            }
            if (isset($data['error'])) {
                $msg = $data['error']['message'] ?? 'YouTube API error';
                return [[], $msg];
            }
            $items = $data['items'] ?? [];
            foreach ($items as $item) {
                $snippet = $item['snippet'] ?? [];
                $resource = $snippet['resourceId'] ?? [];
                $videoId = $resource['videoId'] ?? '';
                if ($videoId === '') {
                    continue;
                }
                $thumbs = $snippet['thumbnails'] ?? [];
                $thumb = $thumbs['high']['url'] ?? ($thumbs['medium']['url'] ?? ($thumbs['default']['url'] ?? ''));
                $videos[] = [
                    'video_id' => $videoId,
                    'title' => $snippet['title'] ?? 'Video',
                    'published' => $snippet['publishedAt'] ?? '',
                    'thumbnail' => $thumb !== '' ? $thumb : 'https://i.ytimg.com/vi/' . $videoId . '/hqdefault.jpg',
                    'url' => 'https://www.youtube.com/watch?v=' . rawurlencode($videoId),
                ];
            }
            $pageToken = $data['nextPageToken'] ?? '';
            if (count($videos) >= 200) {
                break;
            }
        } while ($pageToken !== '');

        return [$videos, null];
    }

    private static function httpGet($url) {
        $ctx = stream_context_create([
            'http' => [
                'timeout' => 12,
                'header' => "User-Agent: KTI-Website/1.0\r\nAccept: application/xml, application/json, */*\r\n",
            ],
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true,
            ],
        ]);
        $body = @file_get_contents($url, false, $ctx);
        return $body !== false ? $body : null;
    }
}
