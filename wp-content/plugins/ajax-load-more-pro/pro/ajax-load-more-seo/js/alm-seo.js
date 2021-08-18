/*
 * Ajax Load More - SEO
 * https://connekthq.com/plugins/ajax-load-more/add-ons/search-engine-optimization/
 * Copyright Connekt Media - https://connekthq.com
 * Author: Darren Cooney
 * Twitter: @KaptonKaos, @connekthq
 */
var alm_seo = {};

(function() {


   /* Set Up Vars */
   alm_seo.init = true;
   alm_seo.paging = false;
   alm_seo.previousUrl = window.location.href;
   alm_seo.isAnimating = false;
   alm_seo.defaultPage = 1;
   alm_seo.fromPopstate = false;
   alm_seo.HTMLHead = document.getElementsByTagName('head')[0].innerHTML;
   alm_seo.nested = false;
   alm_seo.timer = null;
   alm_seo.isIE = (navigator.appVersion.indexOf("MSIE 10") !== -1) ? true : false;
   
   
   
   /**
    * alm_seo	
    * Triggered from core ajax-load-more.js
    *
    * @param {Object} alm
    * @param {Boolean} preloadedInit
    * @since 1.0
    */
   window.almSEO = function(alm, preloadedInit) {

      // Exit if not SEO
      if (!alm.addons.seo) {
         return false;
      }

      alm_seo.nested = (alm.main.dataset.nested === 'true') ? true : false; // Nested ALM instance

      alm_seo.seo_scroll = (alm.addons.seo_scroll === undefined) ? 'false' : alm.addons.seo_scroll;
      alm_seo.scroll = (alm.addons.seo_scroll === 'true') ? true : false; // Scrolling enabled

      alm_seo.canonical_url = alm.canonical_url;
      alm_seo.slug = alm.slug;
      alm_seo.permalink = alm.addons.seo_permalink; // Get permalink type
      alm_seo.pageview = alm.addons.seo_pageview; // Send Google Analytics pageviews
      alm_seo.postsPerPage = alm.posts_per_page; // Get posts_per_page value
      alm_seo.totalposts = alm.totalposts;

      // If preloaded, count the total posts
      alm_seo.totalposts = (alm.addons.preloaded_amount) ? parseInt(alm.addons.preloaded_amount) + alm_seo.totalposts : alm_seo.totalposts;

      alm_seo.totalpages = Math.ceil(alm_seo.totalposts / alm_seo.postsPerPage); // Get total pages
      alm_seo.preloaded = (alm.addons.preloaded === 'true') ? true : false; // Get Preloaded value
      alm_seo.scrolltop = parseInt(alm.addons.seo_scrolltop); // Scrolltop
      alm_seo.controls = alm.addons.seo_controls; // Enable back/fwd button controls
      alm_seo.controls = (alm_seo.controls === '1') ? true : false;
      alm_seo.newPath = ''; // New URL
      alm_seo.paging = alm.addons.paging;
      alm_seo.content = alm.listing; // Current ALM listing container
      alm_seo.trailingslash = (alm_seo.content.dataset.seoTrailingSlash === 'false') ? '' : '/'; // Add trailing slash to URL
      alm_seo.leadingslash = (alm_seo.content.dataset.seoLeadingSlash === 'true') ? '/' : '';
      
      alm_seo.first = alm_seo.content.querySelector('.alm-seo:first-child');

      if (alm.is_search === undefined) {
         alm.is_search = false;
      }
      // Convert to value of slug for appending to seo url
      alm_seo.search_value = (alm.is_search === 'true') ? alm_seo.slug : '';
      

      var page = alm.page + 1,
         nextpage = (alm_seo.preloaded) ? alm.page + 3 : alm.page + 2,
         start = (alm_seo.preloaded) ? 0 : 1; // If preloaded, then start on page 0


      // Get URLs for current and the next page
      nextpage = (preloadedInit) ? alm.page + 2 : nextpage; // Update nextpage if preloaded
      alm_seo.newPath = alm_seo.getURL(page, start, alm_seo.permalink); // current page
      alm_seo.nextPath = alm_seo.getURL(nextpage, start, alm_seo.permalink); // Upcoming page


      // Exit if Preloaded
      if (preloadedInit) {
         alm_seo.init = false;
         return false;
      }


      // Slide screen to current page
      // Do not scroll if paging add-on is enabled or page == 0
      if (page >= 1 && !alm_seo.paging) {
         if (alm_seo.scroll || alm_seo.init) {

            if (alm_seo.preloaded) { 
					// Preloaded
					
               // If start_page > 0, move user to page
               if (alm_seo.init && alm.start_page > 0) {
                  alm_seo.scrollToPage(page + 1);
               } else {
                  if (alm_seo.scroll) {
                     alm_seo.scrollToPage(page + 1);
                  }
               }
            } else {
	            // Standard
               if (page > 1) {
                  alm_seo.scrollToPage(page);
               }
               
            }
         }
      }

      // If paging & first run. Set defaultPage var
      if (alm_seo.paging && alm_seo.init) {
         alm_seo.defaultPage = page;
         if (alm_seo.preloaded) {
            // If preloaded, add 1 page to defaultPage
            alm_seo.defaultPage = parseInt(alm_seo.content.dataset.seoStartPage) + 1;
         }
      }

      // Set URL for Paging add-on
      if (alm_seo.paging) {
         if (!alm_seo.fromPopstate) {
            alm_seo.setURL(page, alm_seo.newPath);
         } else {
            alm_seo.fromPopstate = false;
         }
      }

      // Set rel/prev links in header
      if (alm_seo.init) {
         if (alm_seo.preloaded) { // Preloaded
            alm_seo.getRelLinks(page + 1, alm_seo.nextPath);
         } else {
            alm_seo.getRelLinks(page, alm_seo.nextPath);
         }
      }
		
		// Delay until user is scrolled to page
		setTimeout(function() {
		   alm_seo.init = false; // Reset alm_seo.init
		}, 250);
      

   };



   /**
    * getURL
    * Get the current page URL
    *
    * @param {Number} page Current page number
    * @param {Number} start First page
    * @param {String} permalink The current permalink
    * @since 1.0
    */
   alm_seo.getURL = function(page, start, permalink) {

      var new_url;
      var querystring = window.location.search; // Get querysting value

      if (alm_seo.permalink === 'default') { // Default (http://example.com/?p=N)

         var url = alm_seo.cleanURL(window.location.toString()); // Full URL

         if (querystring !== '' && page > start) {
            // Does URL have a 'paged' value?
            if (!alm_seo.getQueryVariable('paged')) { // No $paged value
               new_url = url + '&paged=' + page;
            } else { // Has $paged value, let's replace it
               new_url = url.replace(/(paged=)[^\&]+/, '$1' + page);
            }
         } else { // Empty querystring
            if (page > 1) {
               new_url = url + '?paged=' + page;
            } else {
               new_url = url;
            }
         }
      } else { // Pretty (http://example.com/2016/post-name/)

         if (page === 1) {
            new_url = alm_seo.canonical_url + querystring;
         } else {
            new_url = alm_seo.canonical_url + alm_seo.leadingslash + 'page/' + page + alm_seo.trailingslash + querystring;
         }

      }

      return new_url; // Return the updated URL
   };



   /**
    * onScroll
    * Update browser URL on scroll
    *
    * @since 1.0
    */
   alm_seo.onScroll = function() {

      var scrollTop = window.pageYOffset;
		
      if (!alm_seo.isAnimating && !alm_seo.paging && !alm_seo.init) {

         if (alm_seo.timer) {
            window.clearTimeout(alm_seo.timer);
         }

         alm_seo.timer = window.setTimeout(function() {

            // Get container scroll position
            var fromTop = scrollTop + alm_seo.scrolltop;
            var posts = document.querySelectorAll('.alm-seo');
            var url = window.location.href;
            // Loop all posts
            var current = Array.prototype.filter.call(posts, function(n, i) {
	            if (typeof ajaxloadmore.getOffset === 'function') {
	               var divOffset = ajaxloadmore.getOffset(n);
						if (divOffset.top < fromTop){
							return n;
						}
					}
            });

            // Get the data attributes of the current element
            var currentPost = current[current.length - 1];
            var permalink = (currentPost) ? currentPost.dataset.url : '';
            var page = (currentPost) ? currentPost.dataset.page : '';

            // If first page
            if (page === undefined || page === '') {
               page = alm_seo.first.dataset.page;
               permalink = alm_seo.first.dataset.url;
            }

            if (url !== permalink) {
               alm_seo.setURL(page, permalink);
            }

         }, 15);

      }
   };
   window.addEventListener('touchstart', alm_seo.onScroll);
   window.addEventListener('scroll', alm_seo.onScroll);



   /**
    * setURL
    * Set the browser URL to current permalink then scroll user to post
    *
    * @param {String} page
    * @param {String} permalink
    * @since 1.0
    */
   alm_seo.setURL = function(page, permalink) {
	   
      if (alm_seo.nested) return false; // Exit if nested

      var state = {
         page: page,
         permalink: permalink
      };
		
      if (permalink !== alm_seo.previousUrl && !alm_seo.fromPopstate) {
	      
	      if (typeof window.history.pushState === 'function' && !alm_seo.isIE) { 
		      
	         // If pushstate is enabled
	         if (alm_seo.controls) { // pushstate
	            history.pushState(state, window.location.title, permalink);
	         } else { // replaceState
	            history.replaceState(state, window.location.title, permalink);
	         }
	
	         // Callback Function (URL Change)
	         if (typeof almUrlUpdate === 'function') {
	            window.almUrlUpdate(permalink, 'seo');
	         }
	         
         }

         // Google Analytics - send pageview
         if (alm_seo.pageview === 'true') {
	         
            var path = '/' + window.location.pathname;
            
            if (typeof ajaxloadmore.tracking === 'function') {
               ajaxloadmore.tracking(path);
               
            } else {
               // Gtag GA Tracking
               if (typeof gtag === 'function') {
                  gtag('event', 'page_view', {
                     'page_path': path
                  });
               }

               // Deprecated GA Tracking
               if (typeof ga === 'function') {
                  ga('send', 'pageview', path);
               }

               // Monster Insights
               if (typeof __gaTracker === 'function') {
                  __gaTracker('send', 'pageview', path);
               }
            }
         }

         alm_seo.previousUrl = permalink;

      }
      alm_seo.getRelLinks(page, permalink);
      alm_seo.fromPopstate = false;

   };



   /**
    * onpopstate
    * Fires when users click back or forward browser buttons
    *
    * @since 1.0
    */
   alm_seo.onpopstate = function(event) {
	   
	   if (alm_seo.nested) return false; // Exit if nested

      // if wrapper doesnt have data-seo="true" don't fire popstate
      var almListing = document.querySelector('.alm-listing[data-seo="true"]') || document.querySelector('.alm-comments[data-seo="true"]');

      if (almListing) {

         if (!alm_seo.paging) {
	         // Not Paging	
	         
	         alm_seo.fromPopstate = true;                  				
            alm_seo.getPageState(event.state);

         } else { 
	         // Paging

            if ((typeof almSetCurrentPage === 'function') && (typeof almGetParentContainer === 'function') && (typeof almGetObj === 'function')) {

               var current = event.state,
                  obj = window.almGetParentContainer(),
                  alm = window.almGetObj();

               // Check for null state value
               current = (current === null) ? alm_seo.defaultPage : event.state.page;

               // Set popstate flag to true - don't trigger pushstate on url update
               alm_seo.fromPopstate = true;

               if (typeof almSetCurrentPage === 'function') { // Paging addon function
                  window.almSetCurrentPage(current, obj, alm);
               }
            }
         }
      }
   };



   /**
    * popstate
    * Window popstate eventlistener
    *
    * @since 1.0
    */
   window.addEventListener('popstate', function(event) {
      if (typeof window.history.pushState == 'function') {
	      var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
	      document.body.scrollTop = scrollTop;
         alm_seo.onpopstate(event);
      }
   });



   /**
    * removeRelLink
    * remove the <link /> tag
    *
    * @param {Element} rel
    * @since 1.0
    */
   alm_seo.removeRelLink = function(rel) {
      if (rel) {
         rel.parentNode.removeChild(rel); // If exists
      }
   };



   /**
    * getPageState
    * Get the current page number
    *
    * @param {Object} data
    * @since 1.0
    */
   alm_seo.getPageState = function(data) {

      var page;
      if (data === null || data === '') { // Will be null with preloaded, so set -1
         page = -1;
      } else {
         page = data.page;
      }
		
      // Get current ALM instance
      var almListing = document.querySelector('.alm-listing[data-seo="true"]') || document.querySelector('.alm-comments[data-seo="true"]');
      if (almListing) {
         alm_seo.scrollToPage(page);
      }

   };



   /**
    * scrollToPage
    * Scroll page to current element wrapper
    *
    * @param {Number} page
    * @since 1.0
    */
   alm_seo.scrollToPage = function(page) {   
      page = (page === undefined || page === '' || page === '-1' || page === -1) ? alm_seo.first.dataset.page : page; // Current page
      
      if (alm_seo.isAnimating){ return false; } // Exit if animating            
      alm_seo.isAnimating = true;
      
      var target = document.querySelector('.alm-seo[data-page="' + page + '"]');      
      if (target) {
	      
         var offset = (typeof ajaxloadmore.getOffset === 'function') ? ajaxloadmore.getOffset(target).top : target.offsetTop;
         var top = offset - alm_seo.scrolltop + 5;

         // Scroll window to position
         
			if(alm_seo.init || alm_seo.fromPopstate){    
   						 			
   			// First run OR From Popstate
				setTimeout(function(){		
   				// Delay fixes browser popstate issues		
					window.scrollTo(0, top);
					alm_seo.fromPopstate = false;
				}, 5);	
							
			} else {
   			
            // Standard Scroll
            if (typeof ajaxloadmore.almScroll === 'function') {
               ajaxloadmore.almScroll(top);               
            } else {
               window.scrollTo({
                  top: top,
                  behavior: 'smooth'
               });               
            }	                        
         } 
                         
      }

      setTimeout(function() {
         alm_seo.isAnimating = false;
      }, 250); 
      
   };



   /**
    * getRelLinks
    * Set the meta rel links to page <head />.
    *
    * @param {Number} page
    * @param {String} permalink
    * @since 1.7
    */
   alm_seo.getRelLinks = function(page, permalink) {
      page = parseInt(page);
      var prevPage = (parseInt(page) - 1);
      var nextPage = (parseInt(page) + 1);

      alm_seo.setRelLink(prevPage, permalink, 'prev');
      alm_seo.setRelLink(nextPage, permalink, 'next');
   };



   /**
    * setRelLink
    * Set the <link /> tag for next and prev rel links
    *
    * @param {Number} page
    * @param {Number} start
    * @param {String} permalink
    * @since 1.7
    */
   alm_seo.setRelLink = function(page, permalink, type) {

      var rel = document.querySelector('link[rel="' + type + '"]');

      // If 'next' and last page, remove the link rel.
      if (type === 'next' && alm_seo.totalpages < page) {
         alm_seo.removeRelLink(rel);
         return false;
      }

      // If is a paged result
      if ((page >= 1)) {

         var link = alm_seo.getURL(page, 0, permalink); // get the new permalink

         if (rel) {
            // if exists, just update the href value
            rel.href = link;
         } else {
            // doesn't exist. Create it
            rel = document.createElement('link');
            rel.href = link;
            rel.rel = type;
            document.getElementsByTagName('head')[0].appendChild(rel);
         }
         
      } else {
         // Remove <link />
         alm_seo.removeRelLink(rel);
         
      }
   };



   /**
    * cleanURL
    * Removes hash from url
    *
    * @param {String} path
    * @since 1.0
    */
   alm_seo.cleanURL = function(path) {
      var loc = path,
         index = loc.indexOf('#');

      path = (index > 0) ? path.substring(0, index) : path;
      return path;
   };



   /**
    * getQueryVariable
    * Get querysting value
    *
    * @param {String} variable
    * @since 1.0
    */
   alm_seo.getQueryVariable = function(variable) {
      var query = window.location.search.substring(1);
      var vars = query.split('&');
      for (var i = 0; i < vars.length; i++) {
         var pair = vars[i].split('=');
         if (decodeURIComponent(pair[0]) == variable) {
            return decodeURIComponent(pair[1]);
         }
      }
      return false;
   };


})();