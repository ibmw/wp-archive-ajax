    // Some var 
    var $z = jQuery.noConflict();
    
    //
    // Year list action default
    //
    $z(document).ready(function() {
        $z('#archive_years li:last-child').toggleClass('current');
        // Data Req month 
        var data = {
			'action': 'mya_req_month',
			'yr': $z('#archive_years li.current').data('year')
		};
		// ajax req
		$z.post(ajaxurl, data, function(response) {
            $z('#result_month').html(response).find('#archive_months').children('li.active').last().toggleClass('current');
        })
        .always(function() {
            // Data Req article
            var data2 = {
                'action': 'mya_req_article',
                'yr': $z('#archive_years li.current').data('year'),
                'mth': $z('#archive_months li.active.current').data('month')
            };
            // ajax req
            $z.post(ajaxurl, data2, function(response2) {
                $z('#result_article').html(response2);
		    });
            //
            // Month list action on click (1)
            //
            $z('#archive_months li.active').on('click', function() {
                $z('#archive_months li.active.current').toggleClass('current');
                $z(this).toggleClass('current');
                var data2 = {
                        'action': 'mya_req_article',
                        'yr': $z('#archive_years li.current').data('year'),
                        'mth': $z(this).data('month')
                    };
                    // ajax req
                    $z.post(ajaxurl, data2, function(response2) {
                        $z('#result_article').html(response2);
                    });
            });
		});
    });
    
    //
    // Year list action on click
    //
    $z('#archive_years li').on('click', function() {
        $z('#archive_years li.current').toggleClass('current');
        $z(this).toggleClass('current');
        var data = {
			'action': 'mya_req_month',
			'yr': $z(this).data('year')
		};

		// ajax req
		$z.post(ajaxurl, data, function(response) {
			$z('#result_month').html(response).find('#archive_months').children('li.active').last().toggleClass('current');
		})
        .always(function() {
            // Data Req article
            var data2 = {
                'action': 'mya_req_article',
                'yr': $z('#archive_years li.current').data('year'),
                'mth': $z('#archive_months li.active.current').data('month')
            };
            // ajax req
            $z.post(ajaxurl, data2, function(response2) {
                $z('#result_article').html(response2);
		    });
            //
            // Month list action on click (2)
            //
            $z('#archive_months li.active').on('click', function() {
                $z('#archive_months li.active.current').toggleClass('current');
                $z(this).toggleClass('current');
                var data2 = {
                        'action': 'mya_req_article',
                        'yr': $z('#archive_years li.current').data('year'),
                        'mth': $z(this).data('month')
                    };
                    // ajax req
                    $z.post(ajaxurl, data2, function(response2) {
                        $z('#result_article').html(response2);
                    });
            });
		});
    });