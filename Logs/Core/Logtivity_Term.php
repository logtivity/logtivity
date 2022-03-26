<?php

class Logtivity_Term
{
	public function __construct()
	{
		add_action( 'edited_terms', [$this, 'termUpdated'], 10, 2 );
		add_action( 'created_term', [$this, 'termCreated'], 10, 3 );
		add_action( 'delete_term', [$this, 'termDeleted'], 10, 5);
	}

	public function termCreated($term_id, $tt_id, $taxonomy)
	{
		$term = get_term_by('id', $term_id, $taxonomy);

		return Logtivity_Logger::log()
			->setAction('Term Created')
			->setContext($term->name) 
			->addMeta('Term ID', $term->term_id)
			->addMeta('Slug', $term->slug)
			->addMeta('Taxonomy', $term->taxonomy)
			->addMeta('Edit Link', get_edit_term_link($term))
			->send();
	}

	public function termUpdated($term_id, $taxonomy)
	{
		$term = get_term_by('id', $term_id, $taxonomy);

		return Logtivity_Logger::log()
			->setAction('Term Updated')
			->setContext($term->name) 
			->addMeta('Term ID', $term->term_id)
			->addMeta('Slug', $term->slug)
			->addMeta('Taxonomy', $term->taxonomy)
			->addMeta('Edit Link', get_edit_term_link($term))
			->send();
	}

	public function termDeleted($term, $tt_id, $taxonomy, $deleted_term, $object_ids)
	{
		return Logtivity_Logger::log()
			->setAction('Term Deleted')
			->setContext($deleted_term->name) 
			->addMeta('Term ID', $deleted_term->term_id)
			->addMeta('Slug', $deleted_term->slug)
			->addMeta('Taxonomy', $deleted_term->taxonomy)
			->send();
	}
}

$Logtivity_Term = new Logtivity_Term;