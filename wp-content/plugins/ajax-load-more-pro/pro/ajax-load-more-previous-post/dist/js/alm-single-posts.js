/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/index.js":
/*!*************************!*\
  !*** ./src/js/index.js ***!
  \*************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/*
 * Ajax Load More - Single Post
 * https://connekthq.com/plugins/ajax-load-more/add-ons/single-posts/
 * Copyright Connekt Media - https://connekthq.com
 * Author: Darren Cooney
 * Twitter: @KaptonKaos, @connekthq
 */
var almSinglePosts = {};

(function () {
	/**
  * Initial function loaded on page load.
  *
  * @since 2.0
  */
	almSinglePosts.onload = function () {
		almSinglePosts.init = true;
		almSinglePosts.initPageTitle = document.title;
		almSinglePosts.titleTemplate = "";
		almSinglePosts.pageview = true;
		almSinglePosts.animating = false;
		almSinglePosts.scroll = true;
		almSinglePosts.offset = 30;
		almSinglePosts.popstate = false;
		almSinglePosts.is_disqus = false;
		almSinglePosts.disableOnScroll = false;
		almSinglePosts.active = true;
		almSinglePosts.target = "";
		almSinglePosts.first = document.querySelector(".alm-single-post");
		almSinglePosts.isIE = navigator.appVersion.indexOf("MSIE 10") !== -1 ? true : false;
		almSinglePosts.showProgressBar = false;
	};

	/**
  * Scroll and touchstart events for Single Posts add-on.
  *
  * @since 2.0
  */
	almSinglePosts.onScroll = function () {
		var scrollTop = window.pageYOffset;

		if (almSinglePosts.active && !almSinglePosts.popstate && scrollTop > 1 && !almSinglePosts.disableOnScroll) {
			// Get container scroll position
			var fromTop = scrollTop + almSinglePosts.offset;

			// Get all Single Posts and Next Page (nested) elements.
			// We get all because we could possibly have nested Next Page inside Single Posts.
			var posts = document.querySelectorAll(".alm-single-post, .alm-nextpage");
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
			var id = currentPost ? currentPost.dataset.id : undefined;
			var permalink = currentPost ? currentPost.dataset.url : "";
			var title = currentPost ? currentPost.dataset.title : "";
			var page = currentPost ? currentPost.dataset.page : "";

			// If ID undefined, use the first post data.
			if (id === undefined) {
				currentPost = almSinglePosts.first;
				id = currentPost.dataset.id;
				permalink = currentPost.dataset.url;
				title = currentPost.dataset.title;
			}

			// Set the reading progress bar.
			if (almSinglePosts.showProgressBar) {
				almSinglePosts.almSetProgressBar(id);
			}

			// Set URL, if applicible.
			if (url !== permalink) {
				almSinglePosts.setURL(id, permalink, title, page, currentPost);
			}
		}
	};
	window.addEventListener("touchstart", almSinglePosts.onScroll);
	window.addEventListener("scroll", almSinglePosts.onScroll);

	/**
  * Create Post Preview Element.
  * Triggered from core ajax-load-more.js
  *
  * @since 1.6
  * @param {string} content The content of the ajax response.
  * @param {string} id The post ID.
  * @return {HTMLElemet} The Preview Elements.
  */
	window.almSinglePostCreatePreview = function (content, id, data) {
		// Create Preview.
		var preview = document.createElement("div");
		preview.classList.add("alm-post-preview");

		// Create Button.
		var button = document.createElement("button");
		button.type = "button";
		button.classList.add("alm-post-preview--button");
		button.innerHTML = data.button_label;
		button.dataset.target = "post-" + id;
		button.addEventListener("click", function () {
			almSinglePosts.closePreview(this, data.className);
		});
		preview.appendChild(button);

		// Create Wrapper.
		var wrapper = document.createElement("div");
		wrapper.classList.add(data.className);
		wrapper.innerHTML = content.innerHTML;
		wrapper.style.maxHeight = data.height + "px";
		if (data.element === "default") {
			// Attach to wrapper.
			wrapper.appendChild(preview);
		} else {
			// Attach to custom element.
			var targetElement = wrapper.querySelector(data.element);
			if (targetElement) {
				targetElement.appendChild(preview);
			} else {
				// If `targetElement` is not found.
				wrapper.appendChild(preview);
			}
		}

		return wrapper;
	};

	/**
  * Click Handler: Close Post Preview
  *
  * @param {element} button   The Button.
  * @param {string} className The contaienr class.
  * @since 1.6
  */
	almSinglePosts.closePreview = function (button, className) {
		if (!button) {
			return false;
		}

		var target = button.dataset.target;
		var targetElement = document.querySelector(".alm-single-post." + target + " ." + className);

		if (!targetElement) {
			return false;
		}
		button.parentNode.style.display = "none";
		targetElement.style.maxHeight = "none";

		// Move focus to target container.
		setTimeout(function () {
			targetElement.focus({ preventScroll: true });
		}, 50);
	};

	/**
  * Main Single Post function.
  * Triggered from core ajax-load-more.js
  *
  * @since 1.0
  * @param {object} alm The Ajax Load More object.
  * @param {string} id The post ID.
  * @param {string} permalink The post permalink.
  * @param {string} title The post title.
  */
	window.almSetSinglePost = function (alm, id, permalink, title) {
		almSinglePosts.titleTemplate = alm.addons.single_post_title_template; // Title Template

		if (almSinglePosts.init) {
			// On init.
			almSinglePosts.siteTitle = alm.addons.single_post_siteTitle; // Site Title
			almSinglePosts.siteTagline = alm.addons.single_post_siteTagline; // Site Tagline
			almSinglePosts.pageview = alm.addons.single_post_pageview && alm.addons.single_post_pageview === "true" ? true : false; // Send pageviews
			almSinglePosts.scroll = alm.addons.single_post_scroll; // Scroll
			almSinglePosts.offset = parseInt(alm.addons.single_post_scroll_top); // Scroll Top
			almSinglePosts.controls = alm.addons.single_post_controls; // Enable back/fwd button controls
			almSinglePosts.controls = almSinglePosts.controls === "1" ? true : false;
			almSinglePosts.scroll = almSinglePosts.scroll === "true" ? true : false;
			almSinglePosts.target = alm.addons.single_post_target !== "" ? alm.addons.single_post_target : false;
			almSinglePosts.progress_bar = alm.addons.single_post_progress_bar; // Progress Bar

			// Set up target
			if (almSinglePosts.target) {
				// Get wrapper
				var singlePostTarget = document.querySelector(almSinglePosts.target);
				if (singlePostTarget) {
					// Get .alm-single div
					var singlePostWrap = document.querySelector(".alm-reveal.alm-single-post");

					// InsertBefore
					singlePostTarget.parentNode.insertBefore(singlePostWrap, singlePostTarget);

					// Append wrapper to .alm-single
					singlePostWrap.appendChild(singlePostTarget);
				}
			}
			// Initiate Progress Bar
			if (almSinglePosts.progress_bar !== "") {
				almSinglePosts.almCreateProgressBar(almSinglePosts.progress_bar);
			}
		}
		// Move to post
		if (almSinglePosts.scroll && !almSinglePosts.init) {
			almSinglePosts.scrollToPost(id);
		}
		almSinglePosts.init = false;
	};

	/**
  * Set the width of the reader progress bar.
  *
  * @since 1.4.2
  * @param {string} id The post ID.
  */
	almSinglePosts.almSetProgressBar = function (id) {
		if (!id || !almSinglePosts.showProgressBar) {
			return false; // Exit if ID null
		}

		var progressDiv = document.querySelector('.alm-reveal.alm-single-post[data-id="' + id + '"]');

		if (progressDiv) {
			var elHeight = Math.round(progressDiv.offsetHeight);
			var wHeight = Math.round(window.outerHeight);
			var scrollT = Math.round(document.documentElement.scrollTop);
			var progressOffset = ajaxloadmore.getOffset(progressDiv);
			var pTop = Math.round(progressOffset.top);

			if (scrollT > parseInt(pTop - almSinglePosts.offset)) {
				// Get Percentage
				var pageEnd = Math.round(wHeight / 1.5);
				var percentage = parseInt(scrollT - pTop + almSinglePosts.offset) / parseInt(elHeight - pageEnd - almSinglePosts.offset) * 100;

				// Set Width
				almSinglePosts.progress.style.width = Math.floor(percentage) + "%";
			} else {
				// Reset
				almSinglePosts.progress.style.width = "0%";
			}
		}
	};

	/**
  * Create the reading progress bar and append to DOM
  *
  * @param {string} style The progress bar style from shortcode.
  * @since 1.4.2
  */
	almSinglePosts.almCreateProgressBar = function (style) {
		if (!style) {
			return false; // Exit if empty
		}

		var barStyle = style.split(":"); // Split shortcode value to access settings
		if (barStyle.length < 3) {
			return false; // Exit, not the correct amount of parameters
		}

		var transition = "all 0.3s linear";
		var transition2 = "all 0.15s linear";
		var body = document.body;

		almSinglePosts.progressWrap = document.createElement("div");
		almSinglePosts.progressWrap.classList.add("alm-reading-progress-wrap");

		almSinglePosts.progress = document.createElement("div");
		almSinglePosts.progress.classList.add("alm-reading-progress");

		almSinglePosts.progressWrap.style.transition = transition;
		almSinglePosts.progress.style.transition = transition2;

		if (barStyle[3]) {
			// Background Color
			almSinglePosts.progressWrap.style.backgroundColor = "#" + barStyle[3];
		}

		almSinglePosts.progressWrap.style.position = "fixed";
		almSinglePosts.progressWrap.style.zIndex = "999999";
		almSinglePosts.progressWrap.style.width = "100%";
		almSinglePosts.progressWrap.style.boxShadow = "0 0 10px rgba(0, 0, 0, 0.2)";
		almSinglePosts.progressWrap.style.opacity = "0";

		// Position Progress Bar
		if (barStyle[0] === "bottom") {
			almSinglePosts.progressWrap.style.bottom = "0";
		} else {
			almSinglePosts.progressWrap.style.top = "0";
		}
		almSinglePosts.progressWrap.style.left = "0";

		// Height
		almSinglePosts.progressWrap.style.height = barStyle[1] + "px";
		almSinglePosts.progress.style.height = barStyle[1] + "px";

		almSinglePosts.progress.style.width = "0";

		// Foreground Color
		almSinglePosts.progress.style.backgroundColor = "#" + barStyle[2];

		// Append to body
		body.appendChild(almSinglePosts.progressWrap);
		almSinglePosts.progressWrap.appendChild(almSinglePosts.progress);

		/*
   * Callback
   * Dispatched after element attached to DOM
   *
   */
		if (typeof almReadingProgressAttached === "function") {
			almReadingProgressAttached(almSinglePosts.progressWrap);
		}

		// Fade In
		setTimeout(function () {
			almSinglePosts.progressWrap.style.opacity = "1";
		}, 250);

		// Set flag
		almSinglePosts.showProgressBar = true;
	};

	/**
  * Fires when users click back or forward browser buttons.
  *
  * @since 1.0
  * @param {event} event The popstate event.
  */
	almSinglePosts.onpopstate = function (event) {
		almSinglePosts.disableOnScroll = true;

		// Exit potstate functions if window has hash - this would likely mean an achor link was clicked.
		if (window.location.hash) {
			almSinglePosts.disableOnScroll = false;
			return false;
		}

		if (!almSinglePosts.init && almSinglePosts.active) {
			almSinglePosts.popstate = true;
			var id;
			if (event.state) {
				// State
				id = event.state.postID;
				almSinglePosts.setPageTitle(event.state.title);
			} else {
				// Null State
				id = almSinglePosts.first.dataset.id;
				document.title = almSinglePosts.initPageTitle;
			}

			// Move to post
			almSinglePosts.popstate = true;
			almSinglePosts.scrollToPost(id);
		}

		almSinglePosts.disableOnScroll = false;
	};

	/**
  * Window PopState Event.
  *
  * @since 1.0
  * @param {event} event The window event.
  */
	window.addEventListener("popstate", function (event) {
		if (typeof window.history.pushState == "function") {
			almSinglePosts.onpopstate(event);
		}
	});

	/**
  * Set the browser URL to current permalink.
  *
  * @since 1.0
  * @param {string} id        The current ID.
  * @param {string} permalink The permalink.
  * @param {string} title     The page title.
  * @param {string} page      Current page #.
  * @param {Element} element  The current HTML element.
  */
	almSinglePosts.setURL = function (id, permalink, title, page, element) {
		// If pushstate & not IE10 is enabled
		if (typeof window.history.pushState === "function" && !almSinglePosts.isIE) {
			var nested = element && element.classList.contains("alm-nextpage") ? true : false;

			var state = {
				postID: id,
				permalink: permalink,
				title: title
			};

			// If PushState disabled (ALM Settings) and ! nested ALM instance.
			if (almSinglePosts.controls && !nested) {
				history.pushState(state, title, permalink);
			} else {
				history.replaceState(state, title, permalink);
			}

			// Set page title.
			almSinglePosts.setPageTitle(title);

			// almUrlUpdate (Core ALM Callback)
			if (typeof almUrlUpdate === "function") {
				window.almUrlUpdate(permalink, "single-post");
			}
		}

		// Disqus comments
		if (almSinglePosts.is_disqus) {
			almSinglePosts.disqusLoad(id, permalink, title, page);
		}

		// Send pageviews to Google Analytics.
		if (almSinglePosts.pageview) {
			var path = "/" + window.location.pathname;
			if (typeof ajaxloadmore.tracking === "function") {
				ajaxloadmore.tracking(path);
			} else {
				// Gtag GA Tracking
				if (typeof gtag === "function") {
					gtag("event", "page_view", {
						page_path: path
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
  * Smooth scroll user to current post.
  * @since 1.0
  * @param {string} id
  */
	almSinglePosts.scrollToPost = function (id) {
		var target = document.querySelector(".alm-reveal.alm-single-post.post-" + id);
		if (target) {
			// Confirm target has children, if not move to top of page. (offset fix_
			target = target.hasChildNodes() ? target : document.querySelector("body");

			var offset = typeof ajaxloadmore.getOffset === "function" ? ajaxloadmore.getOffset(target).top : target.offsetTop;
			var top = offset - almSinglePosts.offset + 1;
			if (!top) {
				return false;
			}

			// Scroll window to position

			if (almSinglePosts.popstate) {
				// From Popstate
				setTimeout(function () {
					// Delay fixes browser popstate issues
					window.scrollTo(0, top);
				}, 5);
			} else {
				// Standard Scroll
				if (typeof ajaxloadmore.almScroll === "function") {
					ajaxloadmore.almScroll(top);
				} else {
					window.scrollTo({
						top: top,
						behavior: "smooth"
					});
				}
			}

			// Set popstate flag to false after transition is done
			setTimeout(function () {
				almSinglePosts.popstate = false;
			}, 250);
		}
	};

	/**
  * Set the page title.
  * @since 1.0
  * @param {string} title
  */
	almSinglePosts.setPageTitle = function (title) {
		if (!almSinglePosts.titleTemplate) {
			document.title = document.title;
		} else {
			var str = almSinglePosts.titleTemplate;
			str = str.replace("{site-title}", almSinglePosts.siteTitle); // Replace site title
			str = str.replace("{tagline}", almSinglePosts.siteTagline); // Replace tagline
			str = str.replace("{post-title}", title); // Replace Post Title
			document.title = str;
		}
	};

	/**
  * Load Disqus comments on page init
  *
  * @since 1.2
  * @param {object} container
  */
	almSinglePosts.disqusInit = function (container) {
		var disqus_shortname = container.dataset.disqusShortname; // get Disqus shortname from container

		if (disqus_shortname) {
			// Append #disqus_thread to container
			var disqus = document.createElement("div");
			disqus.id = "disqus_thread";
			container.appendChild(disqus);

			// Load the Disqus JS file
			var file = document.createElement("script");
			file.setAttribute("type", "text/javascript");
			file.setAttribute("src", "//" + disqus_shortname + ".disqus.com/embed.js");
			document.getElementsByTagName("body")[0].appendChild(file);
			almSinglePosts.is_disqus = true;
		}
	};

	if (document.querySelector(".alm-disqus")) {
		almSinglePosts.disqusInit(document.querySelector(".alm-disqus")); // Init Disqus
	}

	/**
  * Load Disqus comments when page comes into view.
  *
  * @since 1.2
  * @param {string} id
  * @param {string} permalink
  * @param {string} title
  * @param {string} page
  */
	almSinglePosts.disqusLoad = function (id, permalink, title, page) {
		var disqus_thread = document.getElementById("disqus_thread");
		if (disqus_thread) {
			var parent = disqus_thread.parentNode,
			    // .alm-disqus
			height = parent.offsetHeight;

			parent.style.minHeight = height + "px"; // Set height of .alm-disqus to prevent page jumping when disqus loads.

			// Hide #disqus_thread
			disqus_thread.style.display = "none";

			// Get Target Div
			var target = document.querySelector('.alm-single-post[data-id="' + id + '"] .alm-disqus');
			if (target) {
				// Append #disqus_thread to new target
				target.appendChild(disqus_thread).style.display = "block";
			}

			// RESET Disqus instance
			// https://help.disqus.com/customer/portal/articles/472107-using-disqus-on-ajax-sites
			DISQUS.reset({
				reload: true,
				config: function config() {
					this.page.identifier = id + " " + permalink;
					this.page.url = permalink;
					this.page.title = title;
				}
			});
		}
	};

	// Initiate script.
	if (document.querySelector(".alm-single-post")) {
		almSinglePosts.onload();
	}
})();

/***/ })

/******/ });
//# sourceMappingURL=alm-single-posts.js.map