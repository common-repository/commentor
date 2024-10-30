jQuery(document).ready(function($){

    $('.submit button').on('click', function() {

        console.log($(this));

        let settings = [];

        $('table#commentor_settings input, table#commentor_settings select').each(function(index, element) {
            let objectKey = $(element).attr('name');
            settings.push({
                key: objectKey,
                value: $(element).val()
            });
        });

        let data = {
            action: 'commentor_admin_settings',
            wp_nonce: commentor_data.nonce,
            settings: settings
        };

        $.post(commentor_data.ajax_url, data, function(response) {
            if (response.success) {
                $('.notice p').html(response.data.message);
                $('.notice').slideDown();

                setTimeout(function(){
                    $('.notice').slideUp();
                }, 3000);
            }
        });

    });

    const options = {
        series: commentsCount,
        labels: usersCount,
        chart: {
            width: 380,
            type: 'pie',
        },
        legend: {
            position: 'bottom'
        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return '';
            },
            value: true,
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    const chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();

});
