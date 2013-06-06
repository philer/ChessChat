<?php

/**
 * A standalone controller can represent an individual
 * browser page.
 * @author Philipp Miller
 */
interface StandaloneController extends Controller {
	
	public function handleStandaloneRequest();
		
}
