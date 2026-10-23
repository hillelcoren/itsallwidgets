<?php

namespace App\Support\Export;

/**
 * What the export route may hand out. Anything not listed here is never
 * exported (password_resets, migrations, flutter_events, sessions, cache,
 * cache_locks, jobs, job_batches, failed_jobs...).
 */
class ExportTables
{
    /**
     * This app's tables, with the columns the new app copies (built from
     * database/migrations). users.remember_token is left out on purpose.
     *
     * @var array
     */
    const COPIED = [
        'languages' => [
            'id', 'created_at', 'updated_at', 'name', 'locale',
        ],
        'flutter_channels' => [
            'id', 'created_at', 'updated_at', 'source', 'name', 'channel_id', 'description',
            'custom_url', 'thumbnail_url', 'country', 'is_visible', 'match_all_videos', 'language_id',
        ],
        'users' => [
            'id', 'name', 'email', 'google_id', 'avatar_url', 'created_at', 'updated_at',
            'is_admin', 'is_editor', 'is_pro_iaw', 'is_pro_fe', 'is_pro_fx', 'is_subscribed',
            'count_apps', 'count_events', 'count_artifacts', 'profile_key', 'country_code',
            'is_for_hire', 'last_activity', 'image_url', 'profile_url', 'website_url', 'github_url',
            'youtube_url', 'twitter_url', 'medium_url', 'linkedin_url', 'instagram_url', 'handle',
            'bio', 'is_pro', 'is_mentor', 'is_trainer', 'channel_id', 'rss_feed_url', 'widget',
            'widget_voice', 'widget_youtube_url', 'widget_inheritance', 'widget_version_added',
            'widget_description', 'widget_code_sample', 'widget_tip', 'widget_youtube_handle',
            'widget_youtube_comment', 'widget_child_count', 'widget_library',
        ],
        'flutter_apps' => [
            'id', 'created_at', 'updated_at', 'user_id', 'title', 'slug', 'short_description',
            'long_description', 'screenshot1_url', 'website_url', 'repo_url', 'apple_url',
            'google_url', 'facebook_url', 'twitter_url', 'youtube_url', 'apple_released',
            'google_released', 'is_visible', 'has_gif', 'has_screenshot_1', 'has_screenshot_2',
            'has_screenshot_3', 'is_approved', 'instagram_url', 'hash_tags', 'featured',
            'store_review_count', 'store_download_count', 'store_rating', 'category', 'is_mobile',
            'is_web', 'flutter_web_url', 'is_template', 'campaign_id', 'campaign_content_score',
            'campaign_video_score', 'campaign_support_score', 'campaign_subscribe',
            'campaign_comments', 'campaign_is_first_app', 'is_desktop', 'microsoft_url',
            'snapcraft_url', 'has_desktop_gif', 'background_colors', 'background_rotation',
            'font_color',
        ],
        'flutter_artifacts' => [
            'id', 'created_at', 'updated_at', 'user_id', 'title', 'slug', 'url', 'comment', 'type',
            'source_url', 'repo_url', 'image_url', 'gif_url', 'published_date', 'is_visible',
            'is_approved', 'meta_description', 'meta_author', 'meta_author_url',
            'meta_author_twitter', 'meta_publisher', 'meta_publisher_twitter', 'contents',
        ],
        'podcast_episodes' => [
            'id', 'created_at', 'updated_at', 'title', 'email', 'avatar_url', 'short_description',
            'long_description', 'private_notes', 'episode', 'download_count', 'file_size',
            'file_duration', 'reddit_url', 'website_url', 'github_url', 'twitter_url', 'app_url',
            'is_visible', 'is_uploaded', 'published_at', 'is_featured',
        ],
        'flutter_streams' => [
            'id', 'created_at', 'updated_at', 'channel_id', 'name', 'slug', 'description',
            'video_id', 'published_at', 'starts_at', 'thumbnail_url', 'duration', 'view_count',
            'like_count', 'comment_count', 'was_tweeted',
        ],
        'user_activities' => [
            'id', 'created_at', 'updated_at', 'user_id', 'activity_id', 'activity_type', 'is_visible',
        ],
    ];

    /**
     * Columns the new app added to this app's tables, sent only with ?ours=1.
     *
     * @var array
     */
    const OUR_COLUMNS = [
        'flutter_apps' => ['featured_at', 'links_changed_at'],
        'users' => ['pro_onboarded_at'],
    ];

    /**
     * The new app's own tables in this database, with the key they page by.
     *
     * @var array
     */
    const OURS = [
        'audit_logs' => 'id',
        'deleted_slugs' => 'id',
        'passkeys' => 'id',
        'admin_second_factors' => 'id',
        'app_reviews' => 'id',
        'app_store_stats' => 'id',
        'social_posts' => 'id',
        'forum_topics' => 'id',
        'app_submissions' => 'id',
        'handle_redirects' => 'id',
        'reports' => 'id',
        'content_sources' => 'id',
        'artifact_origins' => 'id',
        'user_identities' => 'id',
        'activity_matches' => 'id',
        'app_media_variants' => 'app_id',
        'not_found_paths' => 'id',
        'redirects' => 'id',
        'app_icons' => 'app_id',
        'forum_accounts' => 'id',
        'artifact_search_texts' => 'id',
        'deploy_flags' => 'id',
    ];

    /**
     * The deploy_flags rows the new app keeps: exact names...
     *
     * @var array
     */
    const DEPLOY_FLAG_NAMES = [
        'search_console_baseline',
        'identities_backfilled_at',
        'legacy_links_recorded_at',
        'featured_remembered_at',
    ];

    /**
     * ...and name prefixes.
     *
     * @var array
     */
    const DEPLOY_FLAG_PREFIXES = [
        'cred_',
        'link_watermark',
        'launch_confirmed_',
    ];

    /**
     * Tables whose rows are big enough to read fewer at a time.
     *
     * @var array
     */
    const CHUNK_SIZES = [
        'flutter_artifacts' => 25,
    ];

    /**
     * How many rows of a table RowPager reads from the database at a time.
     *
     * @param  string  $table
     * @return int
     */
    public static function chunkSize($table)
    {
        return array_key_exists($table, self::CHUNK_SIZES) ? self::CHUNK_SIZES[$table] : RowPager::CHUNK;
    }

    /**
     * Limit a query to the rows this table may export.
     *
     * @param  string  $table
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Query\Builder
     */
    public static function scope($table, $query)
    {
        if ($table === 'user_activities') {
            $query->where('activity_type', '!=', 'flutter_event');
        }

        if ($table === 'deploy_flags') {
            $query->where(function ($query) {
                $query->whereIn('name', self::DEPLOY_FLAG_NAMES);

                foreach (self::DEPLOY_FLAG_PREFIXES as $prefix) {
                    $query->orWhereRaw('LEFT(name, ?) = ?', [strlen($prefix), $prefix]);
                }
            });
        }

        return $query;
    }
}
