app.initializers.add('server-side-highlight', function(app) {
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
      setting: 'club-1-server-side-highlight.dark_theme_bg_color',
      label: app.translator.trans('club-1-server-side-highlight.admin.dark_theme_bg_color'),
      type: 'color',
    })
    .registerSetting({
      setting: 'club-1-server-side-highlight.dark_theme_text_color',
      label: app.translator.trans('club-1-server-side-highlight.admin.dark_theme_text_color'),
      type: 'color',
    });
});

 module.exports = {}
