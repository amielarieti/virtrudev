jQuery(document).ready(function ($) {
	"use strict";

	var alm_cache = alm_cache || {};

	// Generate ALM Cache
	var generateCache = {
		array: window.alm_cache_array,
		elapsed: document.querySelector("#alm-elapsed-time"),
		iframe: document.querySelector(".iframe-target"),
		list: document.querySelector("ul.alm-generate-cache--list"),
		initBtn: document.querySelector("button.button-alm-generate-cache"),
		pauseBtn: document.querySelector(
			".alm-generate-cache--controls .cache-pause"
		),
		resumeBtn: document.querySelector(
			".alm-generate-cache--controls .cache-resume"
		),
		rebuildBtn: document.querySelector(
			".alm-generate-cache--controls .cache-rebuild"
		),
		txtProcessing: document.querySelector("#alm-cache-processing-txt"),
		txtPaused: document.querySelector("#alm-cache-paused-txt"),
		txtComplete: document.querySelector("#alm-cache-complete-txt"),
		dircount: 0,
		filecount: 0,
		pause: false,
		timer: null,
		elapsed_timer: null,
		elapsed_counter: 0,
		current: 0,
	};

	alm_cache.countRecords = function (dir, file) {
		document.querySelector("#dircount").innerHTML = dir;
		document.querySelector("#filecount").innerHTML = file;
	};

	var seconds = 0,
		minutes = 0,
		hours = 0;

	/**
	 *  Generate the elapsed time of the cache build
	 */

	alm_cache.elapsedTime = function () {
		generateCache.elapsed_timer = setInterval(function () {
			seconds++;
			if (seconds >= 60) {
				seconds = 0;
				minutes++;
				if (minutes >= 60) {
					minutes = 0;
					hours++;
				}
			}
			$("span", generateCache.elapsed).text(
				(hours ? (hours > 9 ? hours : "0" + hours) : "00") +
					":" +
					(minutes ? (minutes > 9 ? minutes : "0" + minutes) : "00") +
					":" +
					(seconds > 9 ? seconds : "0" + seconds)
			);
		}, 1000);
	};

	/**
	 *  Init the cache build process of each
	 */

	alm_cache.buildCache = function () {
		clearInterval(generateCache.timer); // Clear the current timer
		if (generateCache.current < alm_cache_array.length) {
			var max = generateCache.array[generateCache.current].max
				? generateCache.array[generateCache.current].max
				: 9999;
			alm_cache.setIframeSrc(
				generateCache.array[generateCache.current].url,
				generateCache.array[generateCache.current].id,
				max
			);
		} else {
			alm_cache.complete();
		}
	};

	/**
	 *  Set current cache generatation item
	 */

	alm_cache.setCurrent = function () {
		$("li", generateCache.list).eq(generateCache.current).addClass("current");
	};

	/**
	 *  Set `.done` class when cache generatation completes for an item
	 *  @param {Number} i
	 *  @param {boolean} success
	 */

	alm_cache.cacheItemBuilt = function (i, success) {
		if (success) {
			$("li", generateCache.list)
				.eq(i)
				.removeClass("current")
				.addClass("done");
		} else {
			$("li", generateCache.list)
				.eq(i)
				.removeClass("current")
				.addClass("doneError");
		}
	};

	/**
	 *  When the cache build is complete
	 */
	alm_cache.complete = function () {
		generateCache.txtProcessing.style.display = "none";
		generateCache.txtPaused.style.display = "none";
		generateCache.txtComplete.style.display = "block";

		generateCache.pauseBtn.style.display = "none";
		generateCache.resumeBtn.style.display = "none";
		generateCache.rebuildBtn.style.display = "block";

		generateCache.current = 0;
		clearInterval(generateCache.elapsed_timer);
		clearInterval(generateCache.timer);
		//console.log('Cache generation complete');
	};

	/**
	 *  Pause cache generation
	 */

	alm_cache.pause = function () {
		//console.log('Pause');
		generateCache.pause = true;

		generateCache.txtProcessing.style.display = "none";
		generateCache.txtPaused.style.display = "block";
		generateCache.txtComplete.style.display = "none";

		generateCache.pauseBtn.style.display = "none";
		generateCache.resumeBtn.style.display = "block";
		$("li", generateCache.list)
			.eq(generateCache.current)
			.removeClass("current");
		clearInterval(generateCache.elapsed_timer);
	};

	/**
	 *  Resume cache generation
	 */

	alm_cache.resume = function () {
		//console.log('Play');
		generateCache.pause = false;

		generateCache.txtProcessing.style.display = "block";
		generateCache.txtPaused.style.display = "none";
		generateCache.txtComplete.style.display = "none";

		generateCache.pauseBtn.style.display = "block";
		generateCache.resumeBtn.style.display = "none";
		$("li", generateCache.list).eq(generateCache.current).addClass("current");
		alm_cache.elapsedTime();
	};

	/**
	 *  Set iFrame source
	 *  Dynamically update the iframe source
	 */
	alm_cache.setIframeSrc = function (url, id, max) {
		$(generateCache.iframe).html(""); // Clear content from iframe div

		// Create iframe
		$(generateCache.iframe).append(
			$('<iframe id="myIframe" width="100%"></iframe>')
		);
		$("iframe", generateCache.iframe).attr("src", url + "?auto=true");

		// iframe on load
		$("iframe", generateCache.iframe).on("load", function () {
			alm_cache.setCurrent();
			alm_cache.iframeLoaded(
				$("iframe", generateCache.iframe).get(0),
				id,
				max
			);
		});
	};

	/**
	 *  iFrame loaded
	 *  Run function to build cache of current page
	 */
	alm_cache.iframeLoaded = function (frame, id, max) {
		var alm = frame.contentWindow.document.querySelector(
			'.alm-listing[data-cache-id="' + id + '"]'
		);
		var alm_pages_created = 0;

		if (alm) {
			// Cache found

			var parent = alm.parentNode;
			var btn = parent.querySelector(".alm-load-more-btn");
			generateCache.timer = setInterval(function () {
				if (!generateCache.pause) {
					// Exit if paused

					// Finished or Max Pages reached
					if (btn.classList.contains("done") || alm_pages_created > max) {
						alm_cache.cacheItemBuilt(generateCache.current, true);
						generateCache.current++; // Increament the index
						alm_cache.buildCache(); // Build new cache
						generateCache.dircount++;
						alm_cache.countRecords(
							generateCache.dircount,
							generateCache.filecount
						);
					}

					// Still work to do
					else {
						// If ALM not loading
						if (!parent.classList.contains("alm-loading")) {
							generateCache.filecount++;
							alm_pages_created++;
							btn.click();
							alm_cache.countRecords(
								generateCache.dircount,
								generateCache.filecount
							);
						}
					}
				}
			}, 500);
		} else {
			// Cache Not Found
			alm_cache.cacheItemBuilt(generateCache.current, false);
			generateCache.current++; // Increament the index
			alm_cache.buildCache();
		}
	};

	// Button to Generate Cache
	if (generateCache.initBtn) {
		generateCache.initBtn.addEventListener("click", function () {
			window.location = "admin.php?page=ajax-load-more-cache&action=build";
		});
	}

	// Init Cache Generation
	if (generateCache.array && generateCache.iframe) {
		alm_cache.buildCache();
		alm_cache.elapsedTime();
		generateCache.txtProcessing.style.display = "block";
		//console.log('Auto-Generate Cache');
	}

	// Pause
	if (generateCache.pauseBtn) {
		generateCache.resumeBtn.style.display = "none";
		generateCache.pauseBtn.addEventListener("click", alm_cache.pause);
		generateCache.resumeBtn.addEventListener("click", alm_cache.resume);
	}

	// Rebuild
	if (generateCache.rebuildBtn) {
		generateCache.rebuildBtn.addEventListener("click", function () {
			location.reload();
		});
	}

	/**
	 *  Cache search
	 *  Search all cache and return items matching URL or Cache ID
    *
    *  @since 1.0.0

	 */
	$(".alm-cache-search-wrap input").keyup(function () {
		var val = $.trim($(this).val());
		if (val !== "") {
			$(".alm-dir-listing").each(function (e) {
				var el = $(this);
				if (
					$("h3.dir-title", el).text().match(val) ||
					$("ul.cache-details a", el).text().match(val)
				) {
					el.show();
				} else {
					el.hide();
				}
			});
		} else {
			$(".alm-dir-listing").show();
		}
	});

	/**
	 *  Delete the cache, single and all
	 *
	 *  @since 1.0.0
	 */

	alm_cache.deleteCache = function (cache_id, btn, container) {
		$.ajax({
			type: "POST",
			url: alm_cache_localize.ajax_admin_url,
			data: {
				action: "alm_delete_cache",
				cache: cache_id,
				nonce: alm_cache_localize.alm_cache_nonce,
			},
			success: function (response) {
				var speed = container.height() > 500 ? 450 : 300;

				container.slideUp(speed, function () {
					container.removeClass("deleting").remove();

					if ($(".alm-dir-listing").length === 0) {
						// If cache is now empty, redirect to cache dashboard.
						window.location = "admin.php?page=ajax-load-more-cache";
					}
					console.log("Ajax Load More Cache successfully deleted.");
				});
			},
			error: function (xhr, status, error) {
				alert("The was an error and the cache could not be deleted.");
			},
		});
	};

	/**
	 *  Click Handler: Delete induvidiual cache items
	 *
	 *  @since 1.0.0
	 */
	$(document).on("click", ".alm-dir-listing .delete", function (e) {
		var btn = $(this),
			cache_id = btn.data("id"),
			cache_full_path = btn.data("path"),
			container = btn.closest(".alm-dir-listing"),
			msg = window.alm_cache_localize.are_you_sure + "\n" + cache_full_path;

		var r = confirm(msg);
		if (r == true && !$(this).hasClass("deleting")) {
			e.stopPropagation();
			container.addClass("deleting");
			alm_cache.deleteCache(cache_id, btn, container);
		} else {
			e.stopPropagation();
			e.preventDefault();
		}
	});

	/**
	 *  Click Handler: Delete entire cache click event
	 *
	 *  @since 1.0.0
	 */
	$(document).on("click", "form#delete-all-cache button", function (e) {
		var container = $("form#delete-all-cache");
		var path = container.data("path");
		var msg = window.alm_cache_localize.are_you_sure_full + "\n" + path;

		var r = confirm(msg);
		if (!r) {
			e.preventDefault();
		} else {
			container.addClass("deleting");
		}
	});

	/**
	 *  Click Handler: Show full cath path in admin
	 *
	 *  @since 1.7.0
	 */
	$("button.cache-full-path-button").on("click", function () {
		$(this).next("span").css("display", "inline");
		$(this).hide();
	});
});
