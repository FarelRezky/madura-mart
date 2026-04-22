# Purchase Error Fix - TODO

## Status: In Progress

### 1. [✅] Fix PurchaseDetail model
- Add missing fillable fields: expired_date, selling_price
- File: app/Models/PurchaseDetail.php

### 2. [✅] Update PurchaseController index method
- Eager load details.product for index view
- File: app/Http/Controllers/PurchaseController.php

### 3. [✅] Rewrite purchases/index.blade.php
- Fix data structure: group purchases with nested details
- Use correct model fields and relationships
- Fix action links to purchases routes
- File: resources/views/purchases/index.blade.php

### 4. [ ] Test changes
- Visit /purchases index
- Create/edit purchase
- Check laravel.log for errors

### 5. [ ] Complete ✅
