<?php

class Logtivity_Post extends Logtivity_Abstract_Logger
{
	protected $disableUpdateLog = false;
	
	protected $action;

	public function registerHooks()
	{
		add_action( 'transition_post_status', [$this, 'postStatusChanged'], 10, 3);
		add_action( 'save_post', array( $this, 'postWasUpdated' ), 10, 3 );
		add_action( 'wp_trash_post', [$this, 'postWasTrashed'], 10, 1 );
		add_filter('wp_handle_upload', [$this, 'mediaUploaded'], 10, 2);
		add_action('delete_post', [$this, 'postPermanentlyDeleted'], 10, 1);
		add_filter( 'wp_ajax_save-attachment', [$this, 'mediaMetaUpdated'], -1 );
	}

	public function postStatusChanged($new_status, $old_status, $post)
	{
		if ($this->shouldIgnore($post)) {
			return;
		}

		if ($old_status == 'trash') {
			$this->disableUpdateLog = true;
			return $this->postWasRestored($post);
		}

		if ($old_status != 'publish' && $new_status == 'publish') {
			$this->action = $this->getPostTypeLabel($post->ID) . ' Published';
			return;
		}

		if ($old_status == 'publish' && $new_status == 'draft') {
			$this->action = $this->getPostTypeLabel($post->ID) . ' Unpublished';
			return;
		}

		if ($old_status != $new_status) {
			Logtivity_Logger::log()
				->setAction(
					$this->getPostTypeLabel($post->ID) . ' Status changed from '.$old_status.' to '.$new_status
				)
				->setContext($post->post_title)
				->setPostType($post->post_type) 
				->setPostId($post->ID) 
				->send();
		}

	}

	/**
	 * Post was updated or created. ignoring certain auto save system actions
	 * 
	 * @param  integer $post_id 
	 * @param  WP_Post $post    
	 * @param  bool $update
	 * @return void
	 */
	public function postWasUpdated($post_id, $post, $update)
	{
		if ($this->disableUpdateLog) {
			return;
		}

		if ($this->shouldIgnore($post)) {
			return;
		}

		if ($this->loggedRecently($post->ID)) {
			return true;
		}

		$revision = $this->getRevision($post_id);

		Logtivity_Logger::log()
			->setAction($this->action ?? $this->getPostTypeLabel($post->ID) . ' Updated')
			->setContext($post->post_title)
			->setPostType($post->post_type)
			->setPostId($post->ID)
			->addMeta('Post Title', $post->post_title)
			->addMeta('Post Status', $post->post_status)
			->addMetaIf($revision, 'View Revision', $revision)
			->send();

		update_post_meta($post_id, 'logtivity_last_logged', (new \DateTime())->format('Y-m-d H:i:s'));
	}

	private function getRevision( $post_id ) 
	{
		$revisions = wp_get_post_revisions( $post_id );
		if ( ! empty( $revisions ) ) {
			$revision = array_shift( $revisions );
			return $this->getRevisionLink( $revision->ID );
		}
	}

	private function getRevisionLink( $revision_id ) 
	{
		return ! empty( $revision_id ) ? add_query_arg( 'revision', $revision_id, admin_url( 'revision.php' ) ) : null;
	}

	public function postWasTrashed($post_id)
	{
		if (get_post_type($post_id) == 'customize_changeset') {
			return;
		}
		
		return Logtivity_Logger::log()
			->setAction($this->getPostTypeLabel($post_id) . ' Trashed')
			->setContext(logtivity_get_the_title($post_id))
			->setPostType(get_post_type($post_id))
			->setPostId($post_id)
			->addMeta('Post Title', logtivity_get_the_title($post_id))
			->send();
	}

	public function postWasRestored($post)
	{
		return Logtivity_Logger::log()
			->setAction(
				$this->getPostTypeLabel($post->ID) . ' Restored from Trash'
			)
			->setContext($post->post_title)
			->setPostType($post->post_type)
			->setPostId($post->ID)
			->addMeta('Post Title', $post->post_title)
			->send();
	}

	public function postPermanentlyDeleted($post_id)
	{
		if ($this->ignoringPostType(get_post_type($post_id))) {
			return;
		}

		if ($this->ignoringPostTitle(logtivity_get_the_title($post_id))) {
			return;
		}

		return Logtivity_Logger::log()
			->setAction(
				$this->getPostTypeLabel($post_id) . ' Permanently Deleted'
			)
			->setContext(logtivity_get_the_title($post_id))
			->setPostType(get_post_type($post_id))
			->setPostId($post_id)
			->addMeta('Post Title', logtivity_get_the_title($post_id))
			->send();
	}

	public function mediaUploaded($upload, $context)
	{
		Logtivity_Logger::log()
			->setAction('Attachment Uploaded')
			->setContext(basename($upload['file']))
			->addMeta('Url', $upload['url'])
			->addMeta('Type', $upload['type'])
			->addMeta('Context', $context)
			->send();

		return $upload;
	}

	public function mediaMetaUpdated() 
	{
		$post_id = absint($_POST['id']);

		if ($post_id) {
			Logtivity_Logger::log()
				->setAction('Attachment Meta Updated.')
				->addMeta("Media ID", $post_id)
				->addMeta("Changes", ( isset($_POST['changes']) ? $_POST['changes'] : null))
				->send();
		}

		return $post;
	}
}

$Logtivity_Post = new Logtivity_Post;