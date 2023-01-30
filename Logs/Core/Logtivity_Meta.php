<?php

class Logtivity_Meta extends Logtivity_Abstract_Logger
{
	protected $ignoreKeys = [
		'logtivity_last_logged',
		'_edit_lock',
		'_edit_last',
		'_wp_attachment_metadata',
		'_encloseme',
		'_wp_trash_meta_status',
		'_wp_trash_meta_time',
		'_wp_desired_post_slug',
		'_wp_trash_meta_comments_status',
		'_pingme',
	];

	protected $ignorePostTypes = [
		'nav_menu_item',
		'edd_payment',
		'edd_log',
	];

	protected $ignorePostTypesIfNotInAdminArea = [
		'shop_order',
		'dlm_download',
		'dlm_download_version',
	];

	public function __construct()
	{
		add_action('update_post_meta', [$this, 'updatingPostMeta'], 10, 4);
		add_action( "added_post_meta", [$this, 'addedPostMeta'], 10, 4);
		add_action( "delete_post_meta", [$this, 'deletingPostMeta'], 10, 4);
		// add_action('update_comment_meta', [$this, 'updatingCommentMeta'], 10, 4);
		// add_action('update_user_meta', [$this, 'updatingUserMeta'], 10, 4);
		// add_action('update_term_meta', [$this, 'updatingTermMeta'], 10, 4);
	}

	public function deletingPostMeta($meta_id, $object_id, $meta_key, $_meta_value)
	{
		return $this->recordPostMetaChanges($meta_id, $object_id, $meta_key, $_meta_value, 'Deleted');
	}

	public function addedPostMeta($meta_id, $object_id, $meta_key, $_meta_value)
	{
		return $this->recordPostMetaChanges($meta_id, $object_id, $meta_key, $_meta_value, 'Added');
	}

	public function updatingPostMeta($meta_id, $object_id, $meta_key, $_meta_value)
	{
		return $this->recordPostMetaChanges($meta_id, $object_id, $meta_key, $_meta_value, 'Updated');
	}

	public function recordPostMetaChanges($meta_id, $object_id, $meta_key, $_meta_value, $keyword)
	{
		if (in_array($meta_key, $this->ignoreKeys)) {
			return;
		}

		$post_type = get_post_type($object_id);

		if (in_array($post_type, $this->ignorePostTypes)) {
			return;
		}

		if ((in_array($post_type, $this->ignorePostTypesIfNotInAdminArea)) && (!is_admin())) {
			return;
		}

		if ($meta_key === '_thumbnail_id') {
			return $this->postThumbnailChanged($meta_id, $object_id, $meta_key, $_meta_value, $keyword);
		}

		$previousValue = get_post_meta($object_id, $meta_key, true);

		if ($previousValue == $_meta_value) {
			return;
		}

		if (get_option('logtivity_enable_post_meta_logging') === "0") {
			return;
		}

		return Logtivity_Logger::log()
			->setAction($this->getPostTypeLabel($object_id) . ' Meta '. $keyword)
			->setContext($meta_key)
			->setPostType(get_post_type($object_id)) 
			->setPostId($object_id) 
			->addMeta('Meta Key', $meta_key)
			->addMeta('Meta Value', $_meta_value)
			->addMeta('Previous Value', $previousValue)
			->addMeta('View Post', get_edit_post_link($object_id))
			->send();
	}

	public function postThumbnailChanged($meta_id, $object_id, $meta_key, $_meta_value, $keyword)
	{
		$previousThumbnailId = get_post_meta($object_id, $meta_key, true);

		return Logtivity_Logger::log()
			->setAction('Post Thumbnail ' . ( $keyword == 'Deleted' ? 'Removed' : 'Changed'))
			->setContext(get_the_title($object_id))
			->setPostType(get_post_type($object_id)) 
			->setPostId($object_id) 
			->addMetaIf($_meta_value != '', 'New Thumbnail ID', $_meta_value)
			->addMetaIf($_meta_value != '', 'New Filename', basename(get_attached_file($_meta_value)))
			->addMetaIf(
				$previousThumbnailId != '' && $previousThumbnailId != $_meta_value, 
				'Previous Thumbnail ID', 
				$previousThumbnailId
			)
			->addMetaIf(
				$previousThumbnailId != '' && $previousThumbnailId != $_meta_value, 
				'Previous Filename', 
				basename(get_attached_file($previousThumbnailId))
			)
			->addMeta('View Post', get_edit_post_link($object_id))
			->send();
	}
}

$Logtivity_Meta = new Logtivity_Meta;