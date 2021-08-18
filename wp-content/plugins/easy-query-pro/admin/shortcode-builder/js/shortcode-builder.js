jQuery(document).ready(function ($) {
	"use strict";

	var _ewpq = {},
		output_div = $("#shortcode_output"),
		output = "[easy_query]";

	output_div.text(output); //Init the shortcode output

	// Add Taxonomy Query
	var tax_query_obj = $(".tax-query-wrap").eq(0).clone();
	$(".tax-query-wrap .remove").remove(); // Remove .remove button from first instance

	$("#add-tax-query").on("click", function (e) {
		e.preventDefault();
		var target = $("#taxonomy-target");
		$("#tax-query-relation").fadeIn(250);
		var el = tax_query_obj.clone().hide();
		target.append(el);
		el.fadeIn(250);
		$("#taxonomy-target select").select2();
	});

	// Get Taxonomy Terms
	$(document).on("change", ".taxonomy-select", function () {
		var el = $(this);
		var tax = el.val();
		var container = el.closest(".taxonomy");
		if (tax !== "") get_tax_terms(tax, container);
	});

	/* Delete Tax Query */
	$(document).on("click", ".remove-tax-query", function (e) {
		var el = $(this),
			parent = el.parent(".tax-query-wrap");

		$("select.taxonomy-select", parent).select2("val", "").trigger("change");
		parent.addClass("removing");
		parent.fadeOut(250, function () {
			_ewpq.buildShortcode();
			parent.removeClass("removing");
			parent.remove();
			if ($(".tax-query-wrap").length > 1) {
				$("#tax-query-relation").fadeIn(250);
			} else {
				$("#tax-query-relation").fadeOut(250);
			}
		});
	});

	// Add Meta Query
	var meta_query_obj = $(".meta-query-wrap").eq(0).clone();
	$(".meta-query-wrap .remove").remove(); // Remove .remove button from first instance
	$("select.meta-compare, select.meta-type").select2();

	$("#add-meta-query").on("click", function (e) {
		e.preventDefault();

		var target = $("#meta-query-extended");
		$("input, select", meta_query_obj).val("");
		var el = meta_query_obj.clone().hide();
		target.append(el);
		el.fadeIn(250);
		$("#meta-query-extended select").select2();

		if ($(".meta-query-wrap").length > 1) {
			$("#meta-query-relation").fadeIn(250);
		} else {
			$("#meta-query-relation").fadeOut(250);
		}

		$("select.meta-compare").select2();
	});

	/* Delete Meta Query */
	$(document).on("click", ".remove-meta-query", function (e) {
		var el = $(this);
		el.parent(".meta-query-wrap").addClass("removing");
		el.parent(".meta-query-wrap").fadeOut(250, function () {
			el.parent(".meta-query-wrap").remove();
			_ewpq.buildShortcode();
		});
		if ($(".meta-query-wrap").length > 3) {
			// Show "Add" button if less than 4 $('.meta-query-wrap')
			$("#alm-meta-key .controls button").removeClass("disabled");
		}
	});

	/*
	 *  _ewpq.select2
	 *  Init Select2 select replacement
	 *
	 *  @since 1.0.0
	 */
	_ewpq.select2 = function () {
		// Default Select2
		$(".row select, .cnkt-main select, select.jump-menu")
			.not(".multiple")
			.select2({});

		// multiple
		$(".cnkt .categories select.multiple").select2({
			placeholder: "Select Categories",
		});
		$(".cnkt .tags select.multiple").select2({
			placeholder: "Select Tags",
		});
	};
	_ewpq.select2();

	// Reset all selects
	_ewpq.reset_select2 = function () {
		// Default Select2
		$(".row select, .cnkt-main select, select.jump-menu")
			.not(".multiple")
			.select2();

		// multiple
		$(".cnkt .categories select.multiple").select2();
		$(".cnkt .tags select.multiple").select2();
	};

	/*
	 *  _ewpq.buildShortcode
	 *  Loop sections and build the shortcode
	 *
	 *  @since 1.0.0
	 */

	_ewpq.buildShortcode = function () {
		output = "[easy_query";

		// ---------------------------
		// - Container Options
		// ---------------------------

		var container_type = $(
			".container_type input[name=container_type]:checked"
		).val();
		if (container_type !== "ul" && container_type != undefined)
			output += ' container="' + container_type + '"';

		var container_classes = $(".container_type input[name=classes]").val();
		if (container_classes !== "" && container_classes != undefined)
			output += ' classes="' + container_classes + '"';

		// ---------------------------
		// - Paging
		// ---------------------------

		var paging = $(".paging input[name=enable_paging]:checked").val();
		var paging_style = $("select#paging-style-select").val();
		var paging_color = $("select#paging-color-select").val();
		var paging_arrows = $(".paging-arrows input:checked").val();

		if (paging !== "true" && paging != undefined) {
			output += ' paging="' + paging + '"';
			$("#paging-style-wrap").slideUp(200, "cnkt_easeInOutQuad");
		} else {
			$("#paging-style-wrap").slideDown(200, "cnkt_easeInOutQuad");
			if (paging_style !== "default") {
				output += ' paging_style="' + paging_style + '"';
				$("#paging-nested-style-wrap").slideUp(200, "cnkt_easeInOutQuad");
			}
			if (paging_style !== "null") {
				$("#paging-nested-style-wrap").slideDown(200, "cnkt_easeInOutQuad");
				if (paging_color !== "grey") {
					output += ' paging_color="' + paging_color + '"';
				}
				if (paging_arrows !== "true") {
					output += ' paging_arrows="false"';
				}
			}
		}

		// ---------------------------
		// - Template
		// ---------------------------

		var template = $(".template select").val();
		if (template != "" && template != undefined && template != "default")
			output += ' template="' + template + '"';

		// ---------------------------
		// - Posts Per Page
		// ---------------------------

		var posts_per_page = $(".posts_per_page input").val();
		if (posts_per_page > -2 && posts_per_page != 6) {
			if (posts_per_page == 0) output += ' posts_per_page="-1"';
			else output += ' posts_per_page="' + posts_per_page + '"';
		}

		// ---------------------------
		// - Post Types
		// ---------------------------

		var post_type_count = 0;
		$(".post_types input[type=checkbox]").each(function (e) {
			if ($(this).is(":checked")) {
				post_type_count++;
				if (post_type_count > 1) {
					output += ", " + $(this).data("type");
				} else {
					if ($(this).hasClass("changed")) {
						output += ' post_type="' + $(this).data("type") + "";
					}
				}
			}
		});
		if (post_type_count > 0) output += '"';

		// ---------------------------
		// - Post Format
		// ---------------------------

		var post_format = $(".post_format select").val();
		if (post_format != "" && post_format != undefined)
			output += ' post_format="' + post_format + '"';

		// ---------------------------
		// - Categories
		// ---------------------------

		// IN
		var cat = $(".categories #category-select").val();
		var cat_type = $(
			".categories input[name=category-select-type]:checked"
		).val();
		if (cat !== "" && cat !== undefined && cat !== null)
			output += " " + cat_type + ' ="' + cat + '"';

		// NOT_IN
		var cat_not_in = $(".categories #category-exclude-select").val();
		if (cat_not_in !== "" && cat_not_in !== undefined && cat_not_in !== null)
			output += ' category__not_in="' + cat_not_in + '"';

		// ---------------------------
		// - Tags
		// ---------------------------

		var tag = $(".tags #tag-select").val();
		var tag_type = $(".tags input[name=tag-select-type]:checked").val();
		if (tag !== "" && tag !== undefined && tag !== null)
			output += " " + tag_type + '="' + tag + '"';

		// NOT_IN
		var tag_not_in = $(".tags #tag-exclude-select").val();
		if (tag_not_in !== "" && tag_not_in !== undefined && tag_not_in !== null)
			output += ' tag__not_in="' + tag_not_in + '"';

		// ---------------------------
		// - Taxonomy Query
		// ---------------------------

		var taxonmyWrap = $(".tax-query-wrap").eq(0),
			tax = $.trim(taxonmyWrap.find("select.taxonomy-select").val()),
			tax_relation = $("select.tax-relation").val(),
			tax_query_length = $(".tax-query-wrap").length;

		if (tax !== "" && tax !== undefined) {
			var taxonomy = "",
				taxonomy_terms = "",
				taxonomy_operator = "";

			$(".tax-query-wrap").each(function (e) {
				var el = $(this),
					t = $.trim(el.find("select.taxonomy-select").val()),
					to = $.trim(el.find("select.taxonomy-operator").val());

				var tax_term_count = 0;
				var tt = "";
				$(".tax-terms-container input[type=checkbox]", el).each(function (
					e
				) {
					if ($(this).is(":checked")) {
						tax_term_count++;
						if (tax_term_count > 1) {
							tt += ", " + $(this).data("type");
						} else {
							tt += $(this).data("type");
						}
					}
				});

				if (e === 0) {
					// Fire on first only
					taxonomy += t;
					taxonomy_terms += tt;
					taxonomy_operator += to;
				} else {
					if (t.length > 0 && tt.length > 0) {
						taxonomy += ":" + t;
						taxonomy_terms += ":" + tt;
						taxonomy_operator += ":" + to;
					}
				}
			});

			output += ' taxonomy="' + taxonomy + '"';
			output += ' taxonomy_terms="' + taxonomy_terms + '"';
			output += ' taxonomy_operator="' + taxonomy_operator + '"';

			// Display/Set Meta Relation
			if (tax_query_length > 1) {
				$("#tax-query-relation").fadeIn(200, "cnkt_easeInOutQuad");
				output += ' taxonomy_relation="' + tax_relation + '"';
			} else {
				$("#tax-query-relation").fadeOut(200, "cnkt_easeInOutQuad");
			}
		} else {
			$("#tax-query-relation").fadeOut(200, "cnkt_easeInOutQuad");
		}

		// ---------------------------
		// - Date
		// ---------------------------
		var currentTime = new Date(),
			currentYear = currentTime.getFullYear();

		var dateY = $(".date input#input-year").val(); // Year
		if (dateY !== "" && dateY !== undefined && dateY <= currentYear)
			output += ' year="' + dateY + '"';

		var dateM = $(".date input#input-month").val(); // Month
		if (dateM !== "" && dateM !== undefined && dateM < 13)
			output += ' month="' + dateM + '"';

		var dateD = $(".date input#input-day").val(); // Day
		if (dateD !== "" && dateD !== undefined && dateD < 32)
			output += ' day="' + dateD + '"';

		// ---------------------------
		// - Authors
		// ---------------------------

		var author = $(".authors #author-select").val();
		if (author !== "" && author !== undefined)
			output += ' author="' + author + '"';

		// ---------------------------
		// - Search
		// ---------------------------

		var search = $(".search-term input").val();
		search = $.trim(search);
		if (search !== "") output += ' search="' + search + '"';

		// ---------------------------
		// - Archives
		// ---------------------------

		var archives = $(".archives input:checked").val();
		if (archives === "true") output += ' archive="true"';

		// ---------------------------
		// - Custom Arguments
		// ---------------------------

		var custom_args = $(".custom-arguments input").val();
		custom_args = $.trim(custom_args);
		if (custom_args !== "") output += ' custom_args="' + custom_args + '"';

		// ---------------------------
		// - Custom Fields Meta Query
		// ---------------------------
		var meta_key = $.trim(
				$(".meta-query-wrap").eq(0).find("input.meta-key").val()
			),
			meta_value = $.trim(
				$(".meta-query-wrap").eq(0).find("input.meta-value").val()
			),
			meta_compare = $(".meta-query-wrap")
				.eq(0)
				.find("select.meta-compare")
				.val(),
			meta_type = $(".meta-query-wrap").eq(0).find("select.meta-type").val(),
			meta_relation = $("select.meta-relation").val(),
			meta_query_length = $(".meta-query-wrap").length;

		// Set meta_compare default value
		if (meta_compare === "" || meta_compare == undefined) meta_compare = "=";

		// Set meta_type default value
		if (meta_type === "" || meta_type == undefined) meta_type = "CHAR";

		// Single Meta_Query()
		if (meta_query_length === 1) {
			if (meta_key !== "" && meta_key !== undefined) {
				output += ' meta_key="' + meta_key + '"';
				output += ' meta_value="' + meta_value + '"';
				output += ' meta_compare="' + meta_compare + '"';
				output += ' meta_type="' + meta_type + '"';
			}
		}
		// Multiple Meta_Query()
		if (meta_query_length > 1) {
			meta_key = "";
			meta_value = "";
			meta_compare = "";
			meta_type = "";
			$(".meta-query-wrap").each(function (e) {
				var el = $(this),
					mk = $.trim(el.find("input.meta-key").val()),
					mv = $.trim(el.find("input.meta-value").val()),
					mc = $.trim(el.find("select.meta-compare").val()),
					mt = $.trim(el.find("select.meta-type").val());

				if (e === 0) {
					// first on first only
					meta_key += mk;
					meta_value += mv;
					meta_compare += mc;
					meta_type += mt;
				} else {
					if (mk.length > 0 && mv.length > 0) {
						meta_key += ":" + mk;
						meta_value += ":" + mv;
						meta_compare += ":" + mc;
						meta_type += ":" + mt;
					}
				}
			});

			output += ' meta_key="' + meta_key + '"';
			output += ' meta_value="' + meta_value + '"';
			output += ' meta_compare="' + meta_compare + '"';
			output += ' meta_type="' + meta_type + '"';

			var isRelation = $("#meta-query-relation").css("display");
			if (
				meta_relation !== "" &&
				meta_relation !== undefined &&
				isRelation === "block"
			) {
				output += ' meta_relation="' + meta_relation + '"';
			}
		} else {
			$("#meta-query-relation").fadeOut(150);
		}

		// ---------------------------
		// - Include posts
		// ---------------------------

		var include = $("input#include-posts").val();
		include = $.trim(include);
		if (include !== "") {
			//Remove trailing comma, if present
			if (include.charAt(include.length - 1) == ",") {
				include = include.slice(0, -1);
			}
			output += ' post__in="' + include + '"';
		}

		// ---------------------------
		// - Exclude posts
		// ---------------------------

		var exclude = $("input#exclude-posts").val();
		exclude = $.trim(exclude);
		if (exclude !== "") {
			//Remove trailing comma, if present
			if (exclude.charAt(exclude.length - 1) == ",") {
				exclude = exclude.slice(0, -1);
			}
			output += ' post__not_in="' + exclude + '"';
		}

		// ---------------------------
		// - Post Status
		// ---------------------------
		var post_status = $("select#post-status").val();
		if (post_status !== "publish")
			output += ' post_status="' + post_status + '"';

		// ---------------------------
		// - Ordering
		// ---------------------------
		var order = $("select#post-order").val(),
			orderby = $("select#post-orderby").val();
		if (order !== "DESC") output += ' order="' + order + '"';
		if (orderby !== "date") output += ' orderby="' + orderby + '"';

		// ---------------------------
		// - Post Offset
		// ---------------------------

		var offset = $(".offset input").val();
		if (offset > 0) output += ' offset="' + offset + '"';

		output += "]"; //Close shortcode
		output_div.text(output);

		if (output != "[easy_query]") $(".reset-shortcode-builder").show();
		else $(".reset-shortcode-builder").hide();
	};

	/*
	 *  On change events
	 *
	 *  @since 1.0.0
	 */

	//Select 'post' by default
	$(".post_types input[type=checkbox]#chk-post")
		.prop("checked", true)
		.addClass("changed");

	$(document).on("change keyup", ".alm_element", function () {
		$(this).addClass("changed");

		// If post type is not selected, select 'post'.
		if (!$(".post_types input[type=checkbox]:checked").length > 0) {
			$(".post_types input[type=checkbox]#chk-post").prop("checked", true);
		}

		// If Tax Term Operator is not selected, select 'IN'.
		if (!$("#tax-operator-select input[type=radio]:checked").length > 0) {
			$("#tax-operator-select input[type=radio]#tax-in-radio").prop(
				"checked",
				true
			);
		}

		_ewpq.buildShortcode();
	});

	$("input.numbers-only").keydown(function (e) {
		if (
			$.inArray(e.keyCode, [188, 46, 8, 9, 27, 13, 110, 190]) !== -1 ||
			// Allow: Ctrl+A
			(e.keyCode == 65 && e.ctrlKey === true) ||
			// Allow: home, end, left, right, down, up
			(e.keyCode >= 35 && e.keyCode <= 40)
		) {
			// let it happen, don't do anything
			return;
		}
		// Ensure that it is a number and stop the keypress
		if (
			(e.shiftKey || e.keyCode < 48 || e.keyCode > 57) &&
			(e.keyCode < 96 || e.keyCode > 105)
		) {
			if (e.keyCode !== 188) {
				// If keycode is not a comma
				e.preventDefault();
			}
		}
	});

	/*
	 *  Jump to section, Table of contents
	 *
	 *  @since 1.0.0
	 */

	var jumpOptions = "";
	var toc = "";
	$(".row").each(function () {
		if (!$(this).hasClass("no-brd")) {
			// Special case for back 2 top on shortcode builder landing
			var id = $(this).attr("id");
			var title = $(this).find("h3.heading").text();
			jumpOptions += '<option value="' + id + '">' + title + "</option>";
		}
	});

	/* Jump Menu */

	$("select.jump-menu").append(jumpOptions);
	$("select.jump-menu").change(function () {
		var pos = $(this).val();
		if (pos !== "null") {
			$("html,body").animate(
				{
					scrollTop:
						$("#" + pos).offset().top - ($(".intro").height() - 20),
				},
				200,
				"cnkt_easeInOutQuad"
			);
		}
	});

	/* Table of Contents */
	$(".table-of-contents .toc").append(
		'<option value="#">-- Jump to Option --</option>'
	);
	$(".table-of-contents .toc").append(jumpOptions).select2();

	$(".table-of-contents .toc").change(function () {
		var pos = $(this).val();
		if (pos !== "null") {
			$("html,body").animate(
				{
					scrollTop: $("#" + pos).offset().top - 46,
				},
				500,
				"cnkt_easeInOutQuad"
			);
		}
	});

	/*
	 *  get_tax_terms
	 *  Get taxonomy terms via ajax
	 *
	 *  @since 1.0.0
	 */
	function get_tax_terms(tax, container) {
		$(".taxonomy-extended", container).fadeIn(200, "cnkt_easeInOutQuad");
		var placement = $(".tax-terms-container", container);
		placement.html("<p class='loading'>Fetching Terms...</p>");
		$.ajax({
			type: "GET",
			url: window.parent.ewpq_admin_localize.ajax_admin_url,
			data: {
				action: "ewpq_get_tax_terms",
				taxonomy: tax,
				nonce: window.parent.ewpq_admin_localize.ewpq_admin_nonce,
			},
			dataType: "html",
			success: function (data) {
				placement.html(data);
			},
			error: function (xhr, status, error) {
				responseText.html(
					"<p>Error - Something went wrong and the terms could not be retrieved."
				);
			},
		});
	}

	/*
	 *  _ewpq.cnkt_easeInOutQuad
	 *  Custom easing
	 *
	 *  @since 1.0.0
	 */

	$.easing.cnkt_easeInOutQuad = function (x, t, b, c, d) {
		if ((t /= d / 2) < 1) return (c / 2) * t * t + b;
		return (-c / 2) * (--t * (t - 2) - 1) + b;
	};

	/*
	 *  _ewpq.SelectText
	 *  Click to select text
	 *
	 *  @since 1.0.0
	 */

	_ewpq.SelectText = function (element) {
		var doc = document,
			text = doc.getElementById(element),
			range,
			selection;
		if (doc.body.createTextRange) {
			range = document.body.createTextRange();
			range.moveToElementText(text);
			range.select();
		} else if (window.getSelection) {
			selection = window.getSelection();
			range = document.createRange();
			range.selectNodeContents(text);
			selection.removeAllRanges();
			selection.addRange(range);
		}
	};
	$("#shortcode_output").click(function () {
		_ewpq.SelectText("shortcode_output");
	});

	/*
	 *  Reset shortcode builder
	 *
	 *  @since 1.0.0
	 */

	$(document).on("click", ".reset-shortcode-builder a", function () {
		$("#easy-wp-builder-form").trigger("reset");
		_ewpq.reset_select2();
		_ewpq.buildShortcode();
		$(".post_types input[type=checkbox]#chk-post").prop("checked", true);
	});
});
