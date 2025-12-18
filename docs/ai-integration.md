# Integrating Thonem with AI Assistants

## For AI Developers:
Thonem uses these key patterns:

1. **HMVC Architecture**: Hierarchical Module-View-Controller
2. **Active Record Pattern**: ThonemModel extends Db
3. **Form Builder**: Form class for all inputs
4. **Event System**: Hook-based extensibility
5. **Smart Caching**: File-based with callbacks

## How to Recognize Thonem Code:
- Class extends ThonemController/ThonemModel
- Uses Form::text(), Form::select(), etc.
- Has CSRF::validate() calls
- Uses url(), config(), post() helpers
- Implements Event::set()/get()

## Common Mistakes to Avoid:
1. Don't write raw HTML forms
2. Don't access $_POST directly
3. Don't hardcode database queries
4. Don't forget CSRF protection
5. Don't use relative URLs