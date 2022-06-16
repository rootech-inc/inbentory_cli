### Pending Check List

- [ ] Refund
- [x] Discount *
    - [x] Check if general field has input
      - [x] if there is no input
          - show error
      - [x] if there is input **
        - [x] Authenticate User
            - take user credentials
            - pass it to a form to validate
            - if validated, apply discount with swal success
            - else dont apply discount with swal error
- [x] **Void** _Paused_
    - create hidden input with name and id as void_list
    - on item click, add it to void list `void_list` separated by comma
    - 
    - [x] **_JS void function_**
      - get `void_list` value
      - create form data, `function:void,void_list:void_list`
      - post data as form to server
      - if response is done
        - `get_bill()`
      - else
        - throw an error
        - 
    - [x] **_PHP Function**_    
      - Get void list
      - for each `void_list` delete it from bill item
      - return done


- [x] Lookup
- [ ] Keyboard
    - [x] Numeric
    - [ ] Alphabetical

### PO
- [x] Purchase Order
    - [x] Make Pack ID static base on current document
    - [x] Edit Purchase Order
         - If PO is not approved
         - Get PO Number
         - Set session po_number
         - Set action to edit
         - Load po_trans items with this po number
         - If PO is approved through error issues
    - [x] Search Purchase Order
    - [x] PO Navigation
    - [x] Approve PO
    - [x] Delete PO

### GRN
- [x] View document transactions on document
- [x] save tax value when saving grn
- [x] Print document
- [ ] Edit GRN - enable tax change 
- 