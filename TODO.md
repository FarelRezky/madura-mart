# TODO: Implement Image Display in Products Table

## Tasks
- [x] Create new folder for photos: public/images/photos
- [x] Update ProductController to store images in public/images/photos folder
- [x] Update products/index.blade.php to display images from the new photos folder
- [x] Update delete logic in ProductController to handle new storage path
- [x] Update products/edit.blade.php to display images from the new photos folder
- [ ] Test image upload and display functionality
- [ ] Update other related views (create, edit) if necessary for consistency

# TODO: Build Create Purchase Page

## Tasks
- [x] Update PurchaseDetail model with fillable fields and relationships
- [x] Update PurchaseController store method to handle purchase details
- [x] Update create.blade.php with dynamic product rows and calculations
- [x] Add JavaScript for adding/removing product rows and calculating totals

## Notes
- Changed storage from storage/app/public/products to public/images/photos for direct access
- Updated view to use asset('images/' . $item->picture) for image display
- Ensured delete logic removes files from the correct path
