# Thonem Framework Quick Reference for AI

## NEVER DO THIS:
❌ Raw SQL in controllers
❌ echo '<input type="text">'
❌ Manual CSRF checking
❌ Hardcoded URLs
❌ Direct $_POST access

## ALWAYS DO THIS:
✅ Use ThonemModel for database
✅ Use Form:: methods for inputs  
✅ CSRF::validate() for POST
✅ url() function for URLs
✅ Input::post() for filtering

## Quick Patterns:

1. **Controller**: extends BackstageController|AdminController|ApiController
2. **Model**: extends ThonemModel, define $table
3. **View**: use Form::, Template::, Event::
4. **API**: extends ApiController, Response::json()
5. **Security**: CSRF, Input filtering, password()
6. **Cache**: Cache::get() with callback