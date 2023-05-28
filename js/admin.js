app.initializers.add('server-side-highlight', function(app) {
  const themes = JSON.parse(app.data.settings['club-1-server-side-highlight.available_themes']);
  app.extensionData
    .for('club-1-server-side-highlight')
    .registerSetting({
      setting: 'club-1-server-side-highlight.light_theme_bg_color',
      label: app.translator.trans('club-1-server-side-highlight.admin.light_theme_bg_color'),
      type: 'color',
    })
    .registerSetting({
      setting: 'club-1-server-side-highlight.light_theme_text_color',
      label: app.translator.trans('club-1-server-side-highlight.admin.light_theme_text_color'),
      type: 'color',
    })
    .registerSetting({
      setting: 'club-1-server-side-highlight.light_theme_highlight_theme',
      label: app.translator.trans('club-1-server-side-highlight.admin.light_theme_highlight_theme'),
      type: 'select',
      options: themes,
    })
    .registerSetting({
      setting: 'club-1-server-side-highlight.dark_theme_bg_color',
      label: app.translator.trans('club-1-server-side-highlight.admin.dark_theme_bg_color'),
      type: 'color',
    })
    .registerSetting({
      setting: 'club-1-server-side-highlight.dark_theme_text_color',
      label: app.translator.trans('club-1-server-side-highlight.admin.dark_theme_text_color'),
      type: 'color',
    })
    .registerSetting({
      setting: 'club-1-server-side-highlight.dark_theme_highlight_theme',
      label: app.translator.trans('club-1-server-side-highlight.admin.dark_theme_highlight_theme'),
      type: 'select',
      options: themes,
    });
});

 module.exports = {}
