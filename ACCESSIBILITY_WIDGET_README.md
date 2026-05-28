# Accessibility Widget - Documentation

## Overview

A fully functional accessibility widget has been added to your CSIRT website. It provides a floating panel with essential accessibility features that can be applied globally across the entire website.

## Features

The accessibility widget includes:

1. **Font Size Control** (80% - 150%)
   - Increase/decrease text size
   - Persists across sessions
   - Applies to all page elements

2. **Line Height Control** (1.3 - 2.0)
   - Adjust spacing between lines
   - Options: Small, Normal, Medium, Large, Very Large
   - Improves readability

3. **Letter Spacing Control** (0 - 4px)
   - Increase spacing between letters
   - Options: Normal, Medium, Large, Larger, Maximum
   - Helps with reading clarity

4. **Contrast Modes**
   - **Normal**: Default website colors
   - **High**: Maximum contrast (black/white with clear borders)
   - **Dark**: Dark mode with blue accents for readability

5. **Grayscale Toggle**
   - Normal colors or complete grayscale
   - Useful for color-blind users

6. **Reset Button**
   - Restores all settings to defaults
   - Clears localStorage preferences

## File Structure

```
resources/
├── views/
│   └── components/
│       └── accessibility.blade.php    # Main widget component
└── js/
    └── accessibility.js                # Functionality (for Vite build)

public/
├── css/
│   └── accessibility-contrast.css      # Contrast mode styles
└── js/
    └── accessibility.js                # Direct JS (no build needed)

resources/views/layouts/
└── app.blade.php                       # Updated to include widget
```

## Files Created/Modified

### New Files:
- `resources/views/components/accessibility.blade.php` - Widget HTML & inline styles
- `resources/js/accessibility.js` - Widget functionality (for Vite)
- `public/js/accessibility.js` - Direct public JS file
- `public/css/accessibility-contrast.css` - Contrast modes CSS

### Modified Files:
- `resources/views/layouts/app.blade.php` - Added widget inclusion & script/CSS links

## How It Works

### Storage
- **Method**: Browser LocalStorage
- **Scope**: Per browser/device
- **Persistence**: Survives browser restarts
- **Clearing**: Automatic reset button available in widget

### Keyboard Shortcut
- **Ctrl+U** (or **Cmd+U** on Mac) - Open/close accessibility menu
- Also shown in widget button tooltip

### Design Tokens
The widget uses your existing design system:
- Colors: Navy, Ink, White, Border from `style.css`
- Fonts: Inter (body), Barlow Condensed (display)
- Spacing: Consistent with your layout

## Customization

### Change Widget Position
Edit `accessibility.blade.php`, find `.accessibility-widget` CSS:

```css
.accessibility-widget {
    position: fixed;
    bottom: 20px;       /* Change these */
    right: 20px;        /* values */
    ...
}
```

Options:
- Bottom-right: `bottom: 20px; right: 20px;`
- Bottom-left: `bottom: 20px; left: 20px;`
- Top-right: `top: 20px; right: 20px;`
- Top-left: `top: 20px; left: 20px;`

### Customize Contrast Modes
Edit `public/css/accessibility-contrast.css`:

```css
/* HIGH CONTRAST MODE */
html.accessibility-contrast-high {
  --ink: #000000;
  --navy: #000080;
  /* ... etc */
}
```

Modify color variables to match your preferences.

### Add More Features
To add a new accessibility feature to `public/js/accessibility.js`:

1. Add state property in `constructor()`:
   ```javascript
   this.state = {
     fontSize: 100,
     newFeature: false  // Add here
   };
   ```

2. Add button/control in `accessibility.blade.php`

3. Create handler method:
   ```javascript
   applyNewFeature() {
     // Your implementation
   }
   ```

4. Add to `applyPreferences()` method

5. Update `savePreferences()` and `loadPreferences()` automatically handle new state

## Browser Support

✅ Works on all modern browsers:
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Mobile browsers (iOS Safari, Chrome Mobile)

**LocalStorage requirement**: Needs to be enabled (usually default)

## Accessibility Compliance

The widget itself is built with accessibility in mind:

- ✅ ARIA labels and roles
- ✅ Keyboard navigation (Tab, Enter)
- ✅ Keyboard shortcut (Ctrl+U)
- ✅ High contrast support
- ✅ Screen reader friendly
- ✅ Semantic HTML

## Troubleshooting

### Widget Not Appearing
1. Clear browser cache: `Ctrl+Shift+Delete`
2. Check browser console for JavaScript errors: `F12`
3. Verify files are in correct locations:
   - `public/css/accessibility-contrast.css`
   - `public/js/accessibility.js`

### Settings Not Persisting
- Check if LocalStorage is enabled in browser settings
- Try private/incognito window (limited storage)
- Check browser's site data/storage settings

### Styling Issues
1. Ensure `accessibility-contrast.css` is loaded after `style.css`
2. Check for CSS conflicts with other stylesheets
3. Clear browser cache and rebuild if using Vite

### JavaScript Errors
- Check browser console: `F12 → Console tab`
- Look for missing element IDs
- Verify all HTML elements are present in `accessibility.blade.php`

## Performance Impact

- **Minimal**: Uses pure CSS and JavaScript
- **No dependencies**: No external libraries required
- **Small bundle**: ~15KB uncompressed
- **Efficient**: Applies styles only when needed
- **LocalStorage**: Instant preference loading

## Future Enhancements

Possible additions:
- Animation pause/play
- Font family selector (serif, sans-serif, dyslexia-friendly)
- Text alignment options
- Link underline toggle
- Focus indicator enhancement
- Language switching

## Support

For issues or questions about the accessibility widget:

1. Check this documentation
2. Review code comments in the files
3. Test in different browsers
4. Clear cache and reload

## Version History

- **v1.0** - Initial release
  - Font size control
  - Line height control
  - Letter spacing control
  - Three contrast modes
  - Grayscale toggle
  - LocalStorage persistence
  - Keyboard shortcut support

---

**Created**: May 2026
**Compatible with**: Laravel 10+, PHP 8.0+
**License**: Same as your project
