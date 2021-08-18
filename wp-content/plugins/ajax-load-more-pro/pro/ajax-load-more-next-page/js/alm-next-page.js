/*
 * Ajax Load More - Next Page
 * https://connekthq.com/plugins/ajax-load-more/add-ons/next-page/
 * Copyright Connekt Media - https://connekthq.com
 * Author: Darren Cooney
 * Twitter: @KaptonKaos, @connekthq
 */
var almNextpage = {};

(function () {
	// Defaults.
	almNextpage.init = true;
	almNextpage.urls = true;
	almNextpage.pageviews = true;
	almNextpage.animating = false;
	almNextpage.scroll = false;
	almNextpage.popstate = false;
	almNextpage.firstpage = 1;
	almNextpage.offset = 30;
	almNextpage.active = false;
	almNextpage.paging = true;
	almNextpage.previousUrl = window.location.href;
	almNextpage.fromPopstate = false;
	almNextpage.timer = null;
	almNextpage.nested = false;
	almNextpage.titleTemplate = "";
	almNextpage.first = document.querySelector(
		'.alm-listing[data-nextpage="true"] .alm-nextpage:first-child'
	);
	almNextpage.wrap = document.querySelector(
		'.alm-listing[data-nextpage="true"]'
	);
	almNextpage.isIE =
		navigator.appVersion.indexOf("MSIE 10") !== -1 ? true : false;

	/**
	 * Initial vars setup for Next Page;
	 *
	 * @param {Element} el        The `.alm-listing` element.
	 * @param {Element} container The main Ajax Load More container.
	 * @since 1.0
	 */
	almNextpage.setup = function (el, container) {
		almNextpage.nested = container.dataset.nested === "true" ? true : false;

		if (almNextpage.nested) {
			return false; // Exit if nested.
		}

		if (el.dataset.nextpage === "true" && el.dataset.paging !== "true") {
			almNextpage.paging = false;
			almNextpage.active = true;

			// Scroll & Offset.
			almNextpage.scrollOptions = el.dataset.nextpageScroll;
			almNextpage.scrollOptions = almNextpage.scrollOptions.split(":");
			almNextpage.scroll =
				almNextpage.scrollOptions[0] === "false" ||
				almNextpage.scrollOptions[0] === "0"
					? false
					: true; // Convert to boolean
			almNextpage.offset = almNextpage.scrollOptions[1]
				? parseInt(almNextpage.scrollOptions[1])
				: almNextpage.offset;

			// URLs.
			almNextpage.urls = el.dataset.nextpageUrls;
			almNextpage.urls = almNextpage.urls == "true"; // convert to boolean

			// Pageviews.
			almNextpage.pageviews = el.dataset.nextpagePageviews;
			almNextpage.pageviews = almNextpage.pageviews == "true"; // convert to boolean

			// If startpage > 1.
			var startPage = parseInt(el.dataset.nextpageStartpage);

			// If paged, move to current page on page load.
			if (startPage > 1) {
				almNextpage.popstate = almNextpage.fromPopstate = true;

				// Scroll target.
				var target = document.querySelector(
					'.alm-nextpage[data-page="' + parseInt(startPage) + '"]'
				);
				if (target) {
					var offset =
						typeof ajaxloadmore.getOffset === "function"
							? ajaxloadmore.getOffset(target).top
							: target.offsetTop;

					var top = offset - parseInt(almNextpage.offset) + 1;

					if (almNextpage.fromPopstate) {
						// From Popstate.
						window.scrollTo(0, top);
						almNextpage.fromPopstate = false;
					} else {
						// Standard.
						almNextpage.doScroll(top);
					}

					// Delay until user is moved to page.
					setTimeout(function () {
						almNextpage.popstate = false;
					}, 250);
				}
			}
		}
	};

	// If nextpage, run initial set up
	if (almNextpage.first) {
		// Get closest Ajax Load More object (Temp hack)
		var almListing = document.querySelector(
			'.alm-listing[data-nextpage="true"]'
		);
		if (almListing) {
			var container = almListing.parentNode;
			almNextpage.setup(almListing, container);
		}
	}

	/**
	 * Set initial vars - triggered from core ajax-load-more.js
	 *
	 * @param {Object} alm Ajax Load More object
	 * @since 1.0
	 */
	window.almSetNextPageVars = function (alm) {
		almNextpage.paging = alm.addons.paging; // paging
		if (alm.listing.dataset.nextpage === "true") {
			almNextpage.active = true;
		}
	};

	/**
	 * Update browser URL on scroll.
	 *
	 * @since 1.0
	 */
	almNextpage.onScroll = function () {
		var scrollTop = window.pageYOffset;

		if (
			almNextpage.active &&
			!almNextpage.popstate &&
			scrollTop > 1 &&
			!almNextpage.paging
		) {
			if (almNextpage.timer) {
				window.clearTimeout(almNextpage.timer);
			}

			almNextpage.timer = window.setTimeout(function () {
				// Get container scroll position
				var fromTop = scrollTop + almNextpage.offset;
				var posts = document.querySelectorAll(".alm-nextpage");
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
				var total = currentPost ? currentPost.dataset.pages : "";
				var title = currentPost ? currentPost.dataset.title : "";

				if (page === undefined || page === "") {
					page = almNextpage.first.dataset.page;
					permalink = almNextpage.first.dataset.url;
					title = almNextpage.first.dataset.title;
					total = almNextpage.first.dataset.totalPosts;
				}

				if (url !== permalink) {
					almNextpage.setURL(page, permalink, title, total, false);
				}
			}, 10);
		}
	};
	window.addEventListener("touchstart", almNextpage.onScroll);
	window.addEventListener("scroll", almNextpage.onScroll);

	/**
	 * Main NextPage function.
	 * DIspatched from core Ajax Load More to trigger Next Page functionality.
	 *
	 * @param {Object} alm Ajax Load More object
	 * @since 1.0
	 */
	window.almSetNextPage = function (alm) {
		almNextpage.active = true;
		almNextpage.paging = alm.addons.paging; // Paging add-on.
		almNextpage.btnWrap = alm.btnWrap[0];
		almNextpage.titleTemplate = alm.addons.nextpage_title_template;

		// If Paging.
		if (alm.addons.paging) {
			almNextpage.firstpage = alm.listing.dataset.nextpageStartpage;
		}

		// First Run ONLY.
		if (almNextpage.init) {
			// URLS
			almNextpage.urls = alm.addons.nextpage_urls == "true"; // URL Updates
			almNextpage.canonical_url = alm.canonical_url; // canonical url

			// Post Title
			almNextpage.post_title = alm.addons.nextpage_postTitle;

			// Startpage
			almNextpage.startpage = parseInt(alm.addons.nextpage_startpage); // The starting page.

			// Pageviews
			almNextpage.pageviews = alm.addons.nextpage_pageviews; // Send pageviews
			almNextpage.pageviews = almNextpage.pageviews == "true" ? true : false;

			// Scroll & Offset
			almNextpage.scrollOptions = alm.addons.nextpage_scroll;
			almNextpage.scrollOptions = almNextpage.scrollOptions.split(":");
			almNextpage.scroll =
				almNextpage.scrollOptions[0] === "false" ||
				almNextpage.scrollOptions[0] === "0"
					? false
					: true; // convert to boolean
			almNextpage.offset = almNextpage.scrollOptions[1]
				? parseInt(almNextpage.scrollOptions[1])
				: almNextpage.offset;

			// Init
			almNextpage.init = false;
		}

		// Scroll to post
		if (almNextpage.scroll && !almNextpage.paging) {
			almNextpage.fromPopstate = almNextpage.popstate = false;
			almNextpage.scrollToPage(alm.page);
		}

		// Paging - Set URL
		if (almNextpage.paging) {
			almNextpage.setURL(
				parseInt(alm.page) + 1,
				almNextpage.canonical_url,
				almNextpage.post_title,
				alm.totalpages ? alm.totalpages : 0,
				true
			);
		}
	};

	/**
	 * Fires when users click back or forward browser buttons.
	 *
	 * @since 1.0
	 */
	almNextpage.onpopstate = function (event) {
		var page;

		// Exit if nested OR not active
		if (almNextpage.nested || !almNextpage.active) {
			// Safari fix - only fire when active
			return false;
		}

		almNextpage.popstate = almNextpage.fromPopstate = true;

		if (event.state) {
			page = event.state.pageID;
			page = page === "" || page === null ? 1 : page;
		} else {
			if (almNextpage.paging) {
				page = almNextpage.firstpage;
			} else {
				page = almNextpage.first.dataset.page;
			}
		}

		if (almNextpage.paging) {
			// Paging
			// Trigger Paging Nav.
			var button = almNextpage.btnWrap.querySelector(
				'li.num a[data-page="' + parseInt(page) + '"]'
			);
			if (button) {
				button.click();
			}
		} else {
			// Standard.
			var target = almNextpage.getElementByPage(page);
			if (target) {
				var offset =
					typeof ajaxloadmore.getOffset === "function"
						? ajaxloadmore.getOffset(target).top
						: target.offsetTop;
				var top = offset - almNextpage.offset + 1;

				// Delay fixes browser popstate issues
				setTimeout(function () {
					window.scrollTo(0, top);
					almNextpage.previousUrl = event.permalink; // Set previous URL.
					almNextpage.popstate = almNextpage.fromPopstate = false; // Popstate flag reset.
				}, 10);
			}
		}
	};

	/**
	 * Window popstate eventlistener.
	 *
	 * @since 1.0
	 */
	window.addEventListener("popstate", function (event) {
		if (typeof window.history.pushState == "function") {
			almNextpage.onpopstate(event);
		}
	});

	/**
	 * Set the browser URL to current permalink and send pageviews to GA.
	 *
	 * @param {string}  page      Current page number.
	 * @param {string}  permalink Current URL.
	 * @param {string}  title     Current post title.
	 * @param {string}  total     Total # of posts.
	 * @param {boolean} is_paging Is this paging, true/false.
	 */
	almNextpage.setURL = function (page, permalink, title, total, is_paging) {
		if (almNextpage.nested) {
			return false; // Exit if nested
		}

		if (almNextpage.urls) {
			// Paging.
			if (is_paging) {
				if (page > 1) {
					permalink =
						permalink +
						window.alm_nextpage_localize.leading_slash +
						page +
						window.alm_nextpage_localize.trailing_slash;
				} else {
					permalink = permalink;
				}
			}

			// Page Title.
			var pageTitle = almNextpage.getPageTitle(title, page, total);

			// Confirm URLs don't match and not from popstate.
			if (
				permalink !== almNextpage.previousUrl &&
				!almNextpage.fromPopstate
			) {
				if (
					typeof window.history.pushState === "function" &&
					!almNextpage.isIE
				) {
					// State Variable.
					var state = {
						pageID: page,
						permalink: permalink,
						pageTitle: pageTitle,
					};

					if (almNextpage.nested) {
						history.replaceState(state, pageTitle, permalink);
					} else {
						history.pushState(state, pageTitle, permalink);
					}

					// Callback Function (URL Change)
					if (typeof almUrlUpdate === "function") {
						window.almUrlUpdate(permalink, "nextpage");
					}
				}

				almNextpage.sendPageview();
				almNextpage.previousUrl = permalink;
			}

			// Set page title.
			document.title = pageTitle;
			almNextpage.fromPopstate = almNextpage.popstate = false;
		}
	};

	/**
	 * Get the page title.
	 *
	 * @param {string} postTitle
	 * @param {string} page
	 * @param {string} total
	 * @since 1.0
	 */
	almNextpage.getPageTitle = function (postTitle, page, total) {
		var title = document.title;
		if (almNextpage.titleTemplate) {
			var str = almNextpage.titleTemplate;
			str = str.replace(
				"{site-title}",
				alm_localize ? alm_localize.site_title : ""
			); // Replace site title
			str = str.replace(
				"{tagline}",
				alm_localize ? alm_localize.site_tagline : ""
			); // Replace tagline
			str = str.replace("{post-title}", postTitle); // Replace Post Title
			str = str.replace("{page}", page); // Replace Page
			str = str.replace("{total}", total); // Replace Total
			title = str;
		}

		return title;
	};

	/**
	 * Send pageviews to Google Analytics
	 */
	almNextpage.sendPageview = function () {
		if (almNextpage.pageviews) {
			// If pageviews

			var path = window.location.pathname;

			if (typeof ajaxloadmore.tracking === "function") {
				ajaxloadmore.tracking(path);
			} else {
				// Gtag GA Tracking
				if (typeof gtag === "function") {
					gtag("event", "page_view", {
						page_path: path,
					});
				}

				// Deprecated GA Tracking
				if (typeof ga === "function") {
					ga("send", "pageview", path);
				}

				// Monster Insights
				if (typeof __gaTracker === "function") {
					__gaTracker("send", "pageview", path);
				}
			}
		}
	};

	/**
	 * Scroll user to current page.
	 *
	 * @param {Number} page Current page number.
	 */
	almNextpage.scrollToPage = function (page) {
		if (almNextpage.nested) {
			return false; // Exit if nested
		}

		// Get current page number
		page = almNextpage.paging
			? parseInt(page) + 1
			: page + almNextpage.startpage + 1;

		// Get scroll target
		// If paging, send user to top of listing
		var target = almNextpage.paging
			? almNextpage.wrap
			: almNextpage.getElementByPage(page);

		if (target) {
			var offset =
				typeof ajaxloadmore.getOffset === "function"
					? ajaxloadmore.getOffset(target).top
					: target.offsetTop;
			var top = offset - almNextpage.offset + 1;

			if (almNextpage.paging) {
				// Paging
				almNextpage.doScroll(top);
			} else {
				// Standard
				if (almNextpage.fromPopstate) {
					// Popstate
					setTimeout(function () {
						window.scrollTo(0, top);
						almNextpage.fromPopstate = false;
					}, 5);
				} else {
					// Standard
					almNextpage.doScroll(top);
				}
			}
		}
	};

	/**
	 * Dispatch scroll event.
	 *
	 * @param {Number} top The top position of the element.
	 */
	almNextpage.doScroll = function (top) {
		if (!top) {
			return false;
		}

		// Scroll window to position
		if (typeof ajaxloadmore.almScroll === "function") {
			ajaxloadmore.almScroll(top);
		} else {
			window.scrollTo({
				top: top,
				behavior: "smooth",
			});
		}
	};

	/**
	 * Get Next Page element by page number.
	 *
	 * @param {Number} page
	 * @return {Element}
	 */
	almNextpage.getElementByPage = function (page) {
		if (page) {
			var target = document.querySelector(
				'.alm-listing[data-nextpage="true"] .alm-nextpage[data-page="' +
					parseInt(page) +
					'"]'
			);
			return target ? target : "";
		}
	};
})();
