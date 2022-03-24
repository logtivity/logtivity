<?php

class Logtivity_Term
{
	public function __construct()
	{
		add_action( 'edited_terms', [$this, 'termUpdated'], 10, 2 );
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
}

$Logtivity_Term = new Logtivity_Term;