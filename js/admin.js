app.initializers.add('server-side-highlight', function(app) {
  const themes = JSON.parse(app.data.settings['club-1-server-side-highlight.available_themes']);
  app.extensionData
    .for('club-1-server-side-highlight')
    .registerSetting({
      setting: 'club-1-server-side-highlight.light_theme_bg_color',
      label: app.translator.trans('club-1-server-side-highlight.admin.light_theme_bg_color'),
      type: 'color-preview',
    })
    .registerSetting({
      setting: 'club-1-server-side-highlight.light_theme_text_color',
      label: app.translator.trans('club-1-server-side-highlight.admin.light_theme_text_color'),
      type: 'color-preview',
    })
    .registerSetting({
      setting: 'club-1-server-side-highlight.light_theme_highlight_theme',
      label: app.translator.trans('club-1-server-side-highlight.admin.light_theme_highlight_theme'),
      type: 'select',
      options: themes,
    })
    .registerSetting(function () {
      return preview.bind(this)('light');
    })
    .registerSetting({
      setting: 'club-1-server-side-highlight.dark_theme_bg_color',
      label: app.translator.trans('club-1-server-side-highlight.admin.dark_theme_bg_color'),
      type: 'color-preview',
    })
    .registerSetting({
      setting: 'club-1-server-side-highlight.dark_theme_text_color',
      label: app.translator.trans('club-1-server-side-highlight.admin.dark_theme_text_color'),
      type: 'color-preview',
    })
    .registerSetting({
      setting: 'club-1-server-side-highlight.dark_theme_highlight_theme',
      label: app.translator.trans('club-1-server-side-highlight.admin.dark_theme_highlight_theme'),
      type: 'select',
      options: themes,
    })
    .registerSetting(function () {
      return preview.bind(this)('dark');
    });
});

function preview(mode) {
  let bg = encodeURIComponent(this.setting(`club-1-server-side-highlight.${mode}_theme_bg_color`)());
  let text = encodeURIComponent(this.setting(`club-1-server-side-highlight.${mode}_theme_text_color`)());
  let theme = encodeURIComponent(this.setting(`club-1-server-side-highlight.${mode}_theme_highlight_theme`)());
  return m('div.Form-group', m('iframe', {
    src: app.forum.attribute('baseUrl') + `/admin/highlight-preview?bg=${bg}&text=${text}&theme=${theme}`,
    width: 400,
    height: 220,
    style: 'border: none; border-radius: var(--border-radius)',
  }));
}

 module.exports = {}
