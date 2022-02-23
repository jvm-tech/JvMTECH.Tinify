# JvMTECH.Tinify

Tinify JPG and PNG thumbnails in [Neos CMS](https://www.neos.io/).

Inspired by [Sitegeist.Origami](https://github.com/sitegeist/Sitegeist.Origami) and [NeosRulez.TinyPNG](https://github.com/patriceckhart/NeosRulez.TinyPNG)

## Setup

1. Add your [tinypng.com](https://tinypng.com/) API Key
2. Enable or disable formats in the settings
3. Setup your queue: `./flow queue:setup tinify`
4. Run your queue: `./flow job:work tinify`

```yaml
JvMTECH:
  Tinify:
    apiKey: ''
    formats:
      'image/jpeg':
        enabled: true
      'image/png':
        enabled: true
```

---

by [jvmtech.ch](https://jvmtech.ch)
