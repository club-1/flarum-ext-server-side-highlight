let hljsStyle;
let themes;

app.initializers.add('server-side-highlight', function(app) {
  themes = JSON.parse(app.data.settings['club-1-server-side-highlight.available_themes']);
  app.extensionData
    .for('club-1-server-side-highlight')
    .registerSetting(function () {
      return [
        m('div.Form-group', [
          m('label', app.translator.trans('club-1-server-side-highlight.admin.download_theme')),
          m('span.Select', {
            onchange: e => hljsStyle = e.target.value,
            value: hljsStyle,
          }, [
            m('select.Select-input.FormControl', [
              m('option', {disabled: true, selected: !hljsStyle}, app.translator.trans('club-1-server-side-highlight.admin.choose_theme')),
              hljsStyles.map(x => m('option', x)),
            ]),
            m('i.icon.fas.fa-sort.Select-caret'),
          ]),
        ]),
        m('button.Button.Button--primary', {
          onclick: () => download.bind(this)(hljsStyle),
          disabled: !hljsStyle,
        }, app.translator.trans('club-1-server-side-highlight.admin.download')),
        m('hr')
      ];
    })
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
  return m('div.Form-group', m('iframe.highlight-preview', {
    src: app.forum.attribute('baseUrl') + `/api/highlight-preview?bg=${bg}&text=${text}&theme=${theme}`,
  }));
}

function download(style) {
  hljsStyle = null;
  app.request({
    method: 'GET',
    url: '/api/highlight-download',
    params: {name: style},
    errorHandler,
  })
    .then(res => {
      for (const [key, value] of Object.entries(res)) {
        themes[key] = value;
      }
      app.alerts.show({type: 'success'}, app.translator.trans('club-1-server-side-highlight.admin.download_success'));
      m.redraw();
    })
}

function errorHandler(err) {
  let msgs = err.response.errors.filter(e => e.message).map(e => e.message);
  if (msgs.length == 0) {
    app.alerts.show({type: err.alert.type}, err.alert.content);
  } else {
    msgs.forEach(m => app.alerts.show({type: 'error'}, m));
  }
}


 module.exports = {}
