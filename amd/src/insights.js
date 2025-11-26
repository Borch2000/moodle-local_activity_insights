/**
 * AMD module to fetch scores and render a simple Chart.js chart.
 */
define(['jquery'], function($) {
    return {
        init: function() {
            var url = M.cfg.wwwroot + '/local/activity_insights/classes/external/api.php';
            // Instead use core ajax to call the external function via AJAX (we'll call the service through AJAX endpoint)
            // For demo we'll call the REST endpoint created by webservice (if configured).
            var since = 0;
            $.ajax({
                url: M.cfg.wwwroot + '/webservice/rest/server.php',
                method: 'GET',
                data: {
                    wstoken: '',
                    wsfunction: 'local_activity_insights_get_scores',
                    moodlewsrestformat: 'json',
                    since: since
                }
            }).done(function(data) {
                // If token not provided data may be error; for demo show placeholder chart
                var ctx = document.getElementById('aiChart').getContext('2d');
                var labels = ['Low','Medium','High'];
                var counts = [5, 8, 2];
                // Create chart using Chart.js CDN
                if (typeof Chart === 'undefined') {
                    var script = document.createElement('script');
                    script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
                    script.onload = function() {
                        new Chart(ctx, {type: 'bar', data: {labels: labels, datasets: [{label: 'Students by risk', data: counts}]}} );
                    };
                    document.head.appendChild(script);
                } else {
                    new Chart(ctx, {type: 'bar', data: {labels: labels, datasets: [{label: 'Students by risk', data: counts}]}} );
                }
            }).fail(function() {
                // Fallback chart
                var ctx = document.getElementById('aiChart').getContext('2d');
                var labels = ['Low','Medium','High'];
                var counts = [3, 4, 1];
                var script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
                script.onload = function() {
                    new Chart(ctx, {type: 'bar', data: {labels: labels, datasets: [{label: 'Students by risk', data: counts}]}} );
                };
                document.head.appendChild(script);
            });
        }
    };
});
