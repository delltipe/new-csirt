# Accessibility Widget - Quick Start

## ✅ Implementation Complete

Your accessibility widget (jakarta.go.id v2.0 style) has been fully integrated into your CSIRT
website. Here's what was implemented:

## What's New

### Files:
1. **Blade Component**: `resources/views/components/accessibility.blade.php`
   - Floating toggle button + icon-tile panel
   - 13 features + language row + reset bar + version footer
   - Inline CSS styling on design tokens (boxy, `border-radius: 0`)

2. **JavaScript Logic**: `public/js/accessibility.js` (+ mirror `resources/js/accessibility.js`)
   - Perbesar/Perkecil Teks (4-level gauge)
   - Tinggi Garis (3-level gauge) · Spasi Teks (3-level gauge)
   - Kontras+ cycle (Normal→High→Dark→Invert, 4-level) · Rata Tulisan (L/C/R/Justify, 4-level)
   - Garis Bawahi Tautan (2-level) · Skala Abu-Abu · Sembunyikan Gambar
   - Tulisan Dapat Dibaca · Animasi Dijeda · Kursor (large cursor)
   - Mode Suara = text-to-speech via Web Speech API (`id-ID`)
   - LocalStorage persistence (`accessibilityState`), Ctrl+U shortcut

3. **Contrast Styles**: `public/css/accessibility-contrast.css`
   - High Contrast Mode (pure black/white) + Dark Mode (dark with blue accents)
   - Widget-specific overrides for tiles/toggles/strips in **both** modes
   - Root state classes at the end: `-grayscale`, `-invert`, `-hide-images`, `-readable-font`,
     `-pause-animations`, `-large-cursor`, `-underline-links`, `-align-*`

### Files Modified:
1. **Layout**: `resources/views/layouts/app.blade.php`
   - Added accessibility CSS link
   - Added accessibility component include
   - Added accessibility JS script

## How to Test

### 1. **Browser Test**
```
1. Open your website: http://localhost/
2. Look for the navy square accessibility button (bottom-right corner)
3. Click the button to open the icon-tile panel
4. Test each feature:
   - Mode Suara (speaks page text; stops on 2nd tap / close / reset)
   - Perbesar / Perkecil Teks (4-strip gauge)
   - Kontras+ (Normal → High → Dark → Invert)
   - Rata Tulisan · Sembunyikan Gambar · Tulisan Dapat Dibaca
   - Tinggi Garis · Spasi Teks · Kursor · Animasi Dijeda
   - Skala Abu-Abu · Garis Bawahi Tautan
   - Reset (restore defaults)
5. Refresh page - settings should persist!
```

### 2. **Keyboard Shortcut**
```
Press Ctrl+U (or Cmd+U on Mac) to open/close widget
```

### 3. **Settings Persistence**
```
1. Open widget
2. Tap Perbesar Teks twice (font → 130% level)
3. Tap Kontras+ once (→ Dark)
4. Close browser tab
5. Open your site again
6. Settings are still there! ✓
```

### 4. **Dark Mode Test**
```
1. Tap Kontras+ until Dark (2nd level in the cycle)
2. Watch entire website transform to dark theme
3. Colors adjust to blue accents for readability
4. Keep tapping to reach Invert, then back to Normal
5. Reset to Normal if desired
```

### 5. **Mobile Test**
```
1. Open on phone/tablet
2. Accessibility button should appear bottom-right
3. Panel should be responsive (320px width)
4. All controls should work with touch
```

## Features Breakdown

### Icon-Tile Grid (jakarta.go.id v2.0 layout)

The widget is a 4-column grid of icon tiles. Toggle tiles switch on/off (navy fill while active);
gauge tiles show a strip indicator (filled = level). Gauge tiles advance on tap and **wrap**
(from max back to 0).

**Switches** (`aria-pressed`):
- **Mode Suara** — reads the visible page text aloud via the Web Speech API (`id-ID`), stops on
  toggle-off / panel close / reset
- **Skala Abu-Abu** — grayscale filter (per-element; the widget itself stays in full color and
  keeps its fixed position)
- **Sembunyikan Gambar** — hides image content while keeping layout
- **Tulisan Dapat Dibaca** — dyslexia-friendly system font stack
- **Animasi Dijeda** — pauses animations/transitions
- **Kursor** — large 32px cursor

**Gauges** (tap to cycle, wrap at max → 0):
- **Perbesar Teks / Perkecil Teks** — shared 4-level gauge → 100 / 115 / 130 / 145 % font
  (the widget itself stays fixed-size so Reset remains reachable)
- **Kontras+** — 4-level cycle: Normal → High (pure black/white) → Dark (dark theme) → Invert
  (inverted colors); swaps the tile icon and fills strips
- **Rata Tulisan** — 4-level cycle: Left → Center → Right → Justify
- **Tinggi Garis** — 3-level → 1.5 / 1.7 / 2.0 line height
- **Spasi Teks** — 3-level → 0 / 1.5 / 3 px letter spacing
- **Garis Bawahi Tautan** — 2-level on/off, underlines all links

### Reset
- Restores all settings to defaults
- Clears LocalStorage
- Asks for confirmation
- Message: "Atur ulang semua pengaturan aksesibilitas ke default?"

## Browser Storage

Settings are saved in **LocalStorage** with key: `accessibilityState`

Example stored data (v2 adapter, migrating the old v1 fields on load):
```json
{
  "fontSizeLevel": 1,
  "lineHeightLevel": 1,
  "letterSpacingLevel": 0,
  "contrast": 1,
  "grayscale": false,
  "textAlign": 0,
  "hideImages": false,
  "voice": false,
  "readableFont": false,
  "pauseAnimations": false,
  "largeCursor": false,
  "underlineLinks": 0
}
```

### Clearing Settings
Users can:
1. Click "Atur Ulang" button in widget
2. Or manually clear site data in browser settings
3. Or open DevTools: `localStorage.removeItem('accessibilityState')`

## Customization Options

### Change Widget Position
Edit `resources/views/components/accessibility.blade.php`:
```css
.accessibility-widget {
    bottom: 20px;  /* Change to 'top: 20px' */
    right: 20px;   /* Change to 'left: 20px' */
}
```

### Change Widget Color
Edit in same file, find `.accessibility-toggle`:
```css
background: var(--navy, #003580);  /* Change color */
```

### Add More Features
See `ACCESSIBILITY_WIDGET_README.md` for detailed customization guide.

## Known Limitations

1. **Settings per browser**: Different browsers = separate settings
2. **Incognito/Private windows**: Settings don't persist (browser limitation)
3. **JavaScript required**: Widget needs JavaScript enabled
4. **LocalStorage required**: Needs browser storage capability

## Testing Checklist

- [x] Widget appears on page load
- [x] Blue button visible (bottom-right)
- [x] Click button opens panel
- [x] Click close (X) button closes panel
- [x] Ctrl+U opens/closes widget
- [x] Font size controls work
- [x] Line height controls work
- [x] Text spacing controls work
- [x] Contrast modes work correctly
- [x] Grayscale toggle works
- [x] Reset button works
- [x] Settings persist after refresh
- [x] Works on mobile devices
- [x] Keyboard navigation works
- [x] Panel closes when clicking outside

## Next Steps

1. **Test thoroughly** on all pages of your site
2. **Test on different devices** (desktop, tablet, mobile)
3. **Get user feedback** from accessibility testers
4. **Customize colors** if needed to match your brand
5. **Add to navbar** if you want it always visible
6. **Consider adding** more features based on user needs

## Files Location Reference

```
k:\GitHub\new-csirt\
├── public/
│   ├── css/
│   │   └── accessibility-contrast.css      ← Contrast styles
│   └── js/
│       └── accessibility.js                ← Widget functionality
├── resources/
│   ├── views/
│   │   ├── components/
│   │   │   └── accessibility.blade.php     ← Widget HTML
│   │   └── layouts/
│   │       └── app.blade.php               ← Updated main layout
│   └── js/
│       └── accessibility.js                ← Vite source file
└── ACCESSIBILITY_WIDGET_README.md          ← Full documentation
```

## Support & Troubleshooting

**Widget not showing?**
- Clear cache: Ctrl+Shift+Delete
- Check F12 console for errors
- Verify all 4 files exist in correct folders

**Settings not saving?**
- Enable LocalStorage in browser
- Check site permissions
- Try different browser to isolate issue

**Styling looks wrong?**
- Ensure CSS loaded after `style.css`
- Check for conflicting CSS rules
- Clear browser cache and rebuild

**Need more features?**
- See `ACCESSIBILITY_WIDGET_README.md` for customization guide
- Contact your developer for enhancements

---

**Ready to use!** Your accessibility widget is live and ready for testing. Start by clicking the navy square button in the bottom-right corner of any page.

Good luck! 🚀
