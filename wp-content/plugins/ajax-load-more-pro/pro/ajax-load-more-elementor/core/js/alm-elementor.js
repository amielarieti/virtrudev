/*
 * Ajax Load More - Elementor
 * connekthq.com/plugins/ajax-load-more/add-ons/elementor/
 * Copyright Connekt Media - http://connekthq.com
 * Author: Darren Cooney
 * Twitter: @KaptonKaos, @connekthq
 */

var alm_elmtr = {};

(function () {
	/* Set Up Vars */
	alm_elmtr.init = true;
	alm_elmtr.paging = false;
	alm_elmtr.previousUrl = window.location.href;
	alm_elmtr.isAnimating = false;
	alm_elmtr.defaultPage = 1;
	alm_elmtr.fromPopstate = false;
	alm_elmtr.HTMLHead = document.getElementsByTagName("head")[0].innerHTML;
	alm_elmtr.timer = null;
	alm_elmtr.isIE =
		navigator.appVersion.indexOf("MSIE 10") !== -1 ? true : false;

	/**
	 * Triggered from core ajax-load-more.js
	 *
	 * @param {Object} alm
	 * @since 1.0
	 */
	window.almElementor = function (alm) {
		// Exit if not Elementor
		if (!alm.addons.elementor) {
			return false;
		}

		// First run only
		if (alm_elmtr.init) {
			// Container
			alm_elmtr.container = alm.addons.elementor_element;

			// Fwd/Back buttons
			alm_elmtr.controls = alm.addons.elementor_controls;

			// Scrolltop
			alm_elmtr.scrolltop = alm.addons.elementor_scrolltop;

			// First Element
			alm_elmtr.first = alm_elmtr.container.querySelector(
				".alm-elementor:first-child"
			);

			// Paging (Not yet supported)
			alm_elmtr.paging = alm.addons.paging;
		}

		// Delay for effects
		setTimeout(function () {
			alm_elmtr.init = false;
		}, 50);
	};

	/**
	 * Update browser URL on scroll
	 *
	 * @since 1.0
	 */
	alm_elmtr.onScroll = function () {
		var scrollTop = window.pageYOffset;

		if (!alm_elmtr.isAnimating && !alm_elmtr.paging && !alm_elmtr.init) {
			if (alm_elmtr.timer) {
				window.clearTimeout(alm_elmtr.timer);
			}

			alm_elmtr.timer = window.setTimeout(function () {
				// Get container scroll position
				var fromTop = scrollTop + alm_elmtr.scrolltop;
				var posts = document.querySelectorAll(".alm-elementor");
				var url = window.location.href;

				// Loop all posts
				var current = Array.prototype.filter.call(posts, function (n, i) {
					if (typeof ajaxloadmore.getOffset === "function") {
						var divOffset = ajaxloadmore.getOffset(n);
						if (divOffset.top < fromTop) {
							return n;
						}
					}
				});

				// Get the data attributes of the current element
				var currentPost = current[current.length - 1];
				var permalink = currentPost ? currentPost.dataset.url : "";
				var page = currentPost ? currentPost.dataset.page : "";
				var pageTitle = currentPost ? currentPost.dataset.pageTitle : "";

				// If first page
				if (page === undefined || page === "") {
					page = alm_elmtr.first.dataset.page;
					permalink = alm_elmtr.first.dataset.url;
					pageTitle = alm_elmtr.first.dataset.pageTitle;
				}

				if (url !== permalink) {
					alm_elmtr.setURL(page, permalink, pageTitle);
				}
			}, 15);
		}
	};
	window.addEventListener("touchstart", alm_elmtr.onScroll);
	window.addEventListener("scroll", alm_elmtr.onScroll);

	/**
	 * Set the browser URL to current permalink then scroll user to post
	 *
	 * @param string page
	 * @param string permalink
	 * @param string pageTitle
	 * @since 1.0
	 */
	alm_elmtr.setURL = function (page, permalink, pageTitle) {
		var state = {
			page: page,
			permalink: permalink,
		};

		if (permalink !== alm_elmtr.previousUrl && !alm_elmtr.fromPopstate) {
			if (
				typeof window.history.pushState === "function" &&
				!alm_elmtr.isIE
			) {
				// If pushstate is enabled
				if (alm_elmtr.controls) {
					// pushstate
					history.pushState(state, "", permalink);
				} else {
					// replaceState
					history.replaceState(state, "", permalink);
				}

				// Callback Function (URL Change)
				if (typeof almUrlUpdate === "function") {
					window.almUrlUpdate(permalink, "elementor");
				}
			}

			// Update document title
			document.title = pageTitle;

			// Set Previous URL
			alm_elmtr.previousUrl = permalink;
		}

		alm_elmtr.fromPopstate = false;
	};

	/**
	 * Fires when users click back or forward browser buttons
	 *
	 * @param object event
	 * @since 1.0
	 */
	alm_elmtr.onpopstate = function (event) {
		// if wrapper doesnt have data-elementor="posts" don't fire popstate
		var elmtr = document.querySelector(
			'.alm-listing[data-elementor="posts"]'
		);

		if (elmtr) {
			if (!alm_elmtr.paging) {
				// Not Paging
				alm_elmtr.fromPopstate = true;
				alm_elmtr.getPageState(event.state);
			} else {
				// Paging
				if (
					typeof almSetCurrentPage === "function" &&
					typeof almGetParentContainer === "function" &&
					typeof almGetObj === "function"
				) {
					var current = event.state,
						obj = window.almGetParentContainer(),
						alm = window.almGetObj();

					// Check for null state value
					current =
						current === null ? alm_elmtr.defaultPage : event.state.page;

					// Set popstate flag to true - don't trigger pushstate on url update
					alm_elmtr.fromPopstate = true;

					if (typeof almSetCurrentPage === "function") {
						// Paging addon function
						window.almSetCurrentPage(current, obj, alm);
					}
				}
			}
		}
	};

	/**
	 * Window popstate eventlistener
	 *
	 * @since 1.0
	 */
	window.addEventListener("popstate", function (event) {
		if (typeof window.history.pushState == "function") {
			alm_elmtr.onpopstate(event);
		}
	});

	/**
	 * Get the current page number
	 *
	 * @param object data
	 * @since 1.0
	 */
	alm_elmtr.getPageState = function (data) {
		var page;
		if (data === null || data === "") {
			// Will be null with preloaded, so set -1
			page = -1;
		} else {
			page = data.page;
		}

		if (alm_elmtr.container) {
			alm_elmtr.scrollToPage(page);
		}
	};

	/**
	 * Scroll page to current element wrapper
	 *
	 * @param number page
	 * @since 1.0
	 */
	alm_elmtr.scrollToPage = function (page) {
		// Current page
		page =
			page === undefined || page === "" || page === "-1" || page === -1
				? alm_elmtr.first.dataset.page
				: page;

		if (alm_elmtr.isAnimating) {
			// Exit if animating
			return false;
		}
		alm_elmtr.isAnimating = true;

		var target = document.querySelector(
			'.alm-elementor[data-page="' + page + '"]'
		);

		if (target) {
			var offset =
				typeof ajaxloadmore.getOffset === "function"
					? ajaxloadmore.getOffset(target).top
					: target.offsetTop;
			var top = offset - alm_elmtr.scrolltop + 5;

			// Move window to position
			setTimeout(function () {
				// Delay fixes browser popstate issues
				window.scrollTo(0, top);
				alm_elmtr.fromPopstate = false;
			}, 5);
		}

		setTimeout(function () {
			alm_elmtr.isAnimating = false;
		}, 250);
	};
})();
