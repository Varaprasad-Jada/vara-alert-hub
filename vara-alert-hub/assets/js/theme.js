/**
 * Vara Alert Hub: menu, search panel, sub-menus, avatars and the 404 board.
 * Plain JavaScript, no libraries. The site works without it.
 */
( function () {
	'use strict';

	var chevron = '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="m6 9 6 6 6-6"/></svg>';

	/* Menu toggle (phones) */
	var menuButton = document.querySelector( '.menu-toggle' );
	var nav = document.getElementById( 'site-navigation' );
	if ( menuButton && nav ) {
		menuButton.addEventListener( 'click', function () {
			var open = menuButton.getAttribute( 'aria-expanded' ) === 'true';
			menuButton.setAttribute( 'aria-expanded', open ? 'false' : 'true' );
			nav.classList.toggle( 'is-open', ! open );
		} );
	}

	/* Tap-to-open sub-menus */
	var parents = document.querySelectorAll( '.nav-bar .menu-item-has-children, .nav-bar .page_item_has_children' );
	Array.prototype.forEach.call( parents, function ( item ) {
		var link = item.querySelector( ':scope > a' );
		var button = document.createElement( 'button' );
		button.type = 'button';
		button.className = 'ah-sub-toggle';
		button.setAttribute( 'aria-expanded', 'false' );
		button.innerHTML = '<span class="screen-reader-text">' + ( link ? link.textContent : '' ) + '</span>' + chevron;
		button.addEventListener( 'click', function () {
			var open = item.classList.toggle( 'is-open' );
			button.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		} );
		if ( link ) {
			link.insertAdjacentElement( 'afterend', button );
		}
	} );

	/* Search panel */
	var searchButton = document.querySelector( '.search-toggle' );
	var panel = document.getElementById( 'search-panel' );
	if ( searchButton && panel ) {
		var setSearch = function ( open ) {
			panel.hidden = ! open;
			searchButton.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			if ( open ) {
				var field = panel.querySelector( 'input[type="search"]' );
				if ( field ) {
					field.focus();
				}
			}
		};
		searchButton.addEventListener( 'click', function () {
			setSearch( panel.hidden );
		} );
		document.addEventListener( 'keydown', function ( event ) {
			if ( 'Escape' === event.key && ! panel.hidden ) {
				setSearch( false );
				searchButton.focus();
			}
		} );
	}

	/* Comment avatars: hide a photo that did not load so the initials show */
	Array.prototype.forEach.call( document.querySelectorAll( 'img[data-ah-avatar]' ), function ( img ) {
		var hide = function () {
			img.style.display = 'none';
		};
		img.addEventListener( 'error', hide );
		if ( img.complete && 0 === img.naturalWidth ) {
			hide();
		}
	} );

	/* 404 page: show the Go back button */
	var back = document.querySelector( '.ah-go-back' );
	if ( back ) {
		back.hidden = false;
		back.addEventListener( 'click', function () {
			window.history.back();
		} );
	}

	/* 404 page: flip-board animation */
	var board = document.querySelector( '.ah-board__row' ) ? document.querySelector( '.ah-board' ) : null;
	if ( ! board ) {
		return;
	}

	var letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	var reduce = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	var cells = [];

	Array.prototype.forEach.call( board.querySelectorAll( '.ah-board__row' ), function ( row, rowIndex ) {
		var column = 0;
		Array.prototype.forEach.call( row.querySelectorAll( '.ah-bseg' ), function ( seg ) {
			var width = parseInt( seg.getAttribute( 'data-w' ), 10 ) || 0;
			var text = seg.textContent;
			var wrap = document.createElement( 'span' );
			var i;

			while ( text.length < width ) {
				text += ' ';
			}
			wrap.className = 'ah-cells';
			wrap.setAttribute( 'aria-hidden', 'true' );

			for ( i = 0; i < width; i++ ) {
				var cell = document.createElement( 'span' );
				cell.className = 'ah-flap';
				cell.textContent = '\u00a0';
				cell.setAttribute( 'data-final', text.charAt( i ) );
				cell.setAttribute( 'data-delay', String( rowIndex * 260 + column * 18 ) );
				wrap.appendChild( cell );
				cells.push( cell );
				column++;
			}

			var readable = document.createElement( 'span' );
			readable.className = 'screen-reader-text';
			readable.textContent = seg.textContent;
			seg.textContent = '';
			seg.appendChild( readable );
			seg.appendChild( wrap );
		} );
	} );

	cells.forEach( function ( cell ) {
		var finalChar = cell.getAttribute( 'data-final' );
		if ( reduce || ' ' === finalChar ) {
			cell.textContent = ' ' === finalChar ? '\u00a0' : finalChar;
			return;
		}
		var steps = 6 + Math.floor( Math.random() * 6 );
		var count = 0;
		window.setTimeout( function () {
			var timer = window.setInterval( function () {
				count++;
				if ( count >= steps ) {
					cell.textContent = finalChar;
					window.clearInterval( timer );
				} else {
					cell.textContent = letters.charAt( Math.floor( Math.random() * letters.length ) );
				}
			}, 55 );
		}, parseInt( cell.getAttribute( 'data-delay' ), 10 ) );
	} );
}() );
