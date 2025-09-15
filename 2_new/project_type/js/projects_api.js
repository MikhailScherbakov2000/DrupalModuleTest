(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.projectsApiBehavior = {
    attach: function (context, settings) {
      var container = $('#latest-projects-container', context);

      if (container.length === 0) {
        return;
      }

      $.ajax({
        url: drupalSettings.projectsType.ajaxPath,
        dataType: 'json',
        success: function (data) {
          if (data.length === 0) {
            container.html('<p>Проекты не найдены.</p>');
            return;
          }

          var html = '<h2>Последние проекты</h2><ul class="projects-list">';
          $.each(data, function (index, project) {
            html += '<li>';
            if (project.image) {
              html += '<a href="' + project.url + '"><img src="' + project.image + '" alt="' + project.title + '" /></a>';
            }
            html += '<a href="' + project.url + '">' + project.title + '</a>';
            html += '<div class="project-date">' + project.date + '</div>';
            html += '</li>';
          });
          html += '</ul>';

          container.html(html);
        },
        error: function () {
          container.html('<p>Ошибка загрузки проектов.</p>');
        }
      });
    }
  };
})(jQuery, Drupal, drupalSettings);
