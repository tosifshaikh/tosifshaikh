"use strict";
(self["webpackChunklaravel_vue"] = self["webpackChunklaravel_vue"] || []).push([["resources_js_views_Products_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Products.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Products.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "./node_modules/@babel/runtime/regenerator/index.js");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _Services_product_service__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../Services/product_service */ "./resources/js/Services/product_service.js");


function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); enumerableOnly && (symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; })), keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = null != arguments[i] ? arguments[i] : {}; i % 2 ? ownKeys(Object(source), !0).forEach(function (key) { _defineProperty(target, key, source[key]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)) : ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: "Products",
  data: function data() {
    return {
      Products: [],
      categories: [],
      ProductData: {
        category_id: '',
        name: '',
        image: ''
      },
      editProductData: {},
      moreExist: false,
      nextPage: 0,
      errors: {}
    };
  },
  mounted: function mounted() {
    this.loadCategories();
    this.loadProducts();
  },
  methods: {
    editProduct: function editProduct(Product) {
      this.editProductData = _objectSpread({}, Product);
      this.showEditProductModal();
    },
    loadCategories: function () {
      var _loadCategories = _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee() {
        var response;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                _context.prev = 0;
                _context.next = 3;
                return _Services_product_service__WEBPACK_IMPORTED_MODULE_1__.getCategories();

              case 3:
                response = _context.sent;
                this.categories = response.data;
                _context.next = 10;
                break;

              case 7:
                _context.prev = 7;
                _context.t0 = _context["catch"](0);
                this.flashMessage.error({
                  message: 'Some Error Occured!, Please Refresh!',
                  time: 5000
                });

              case 10:
              case "end":
                return _context.stop();
            }
          }
        }, _callee, this, [[0, 7]]);
      }));

      function loadCategories() {
        return _loadCategories.apply(this, arguments);
      }

      return loadCategories;
    }(),
    loadProducts: function () {
      var _loadProducts = _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee2() {
        var response;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee2$(_context2) {
          while (1) {
            switch (_context2.prev = _context2.next) {
              case 0:
                _context2.prev = 0;
                _context2.next = 3;
                return _Services_product_service__WEBPACK_IMPORTED_MODULE_1__.loadProducts();

              case 3:
                response = _context2.sent;
                this.Products = response.data.data;

                if (response.data.current_page < response.data.last_page) {
                  this.moreExist = true;
                  this.nextPage = response.data.current_page + 1;
                } else {
                  this.moreExist = false;
                }

                _context2.next = 11;
                break;

              case 8:
                _context2.prev = 8;
                _context2.t0 = _context2["catch"](0);
                this.flashMessage.error({
                  message: 'Some Error Occured!, Please Refresh!',
                  time: 5000
                });

              case 11:
              case "end":
                return _context2.stop();
            }
          }
        }, _callee2, this, [[0, 8]]);
      }));

      function loadProducts() {
        return _loadProducts.apply(this, arguments);
      }

      return loadProducts;
    }(),
    attachImage: function attachImage() {
      //to use file reader
      this.ProductData.image = this.$refs.newProductImage.files[0];
      var reader = new FileReader();
      reader.addEventListener('load', function () {
        this.$refs.newProductImageDisplay.src = reader.result;
      }.bind(this), false);
      reader.readAsDataURL(this.ProductData.image);
    },
    editAttachImage: function editAttachImage() {
      //to use file reader
      this.editProductData.image = this.$refs.editProductImage.files[0];
      var reader = new FileReader();
      reader.addEventListener('load', function () {
        this.$refs.editProductImageDisplay.src = reader.result;
      }.bind(this), false);
      reader.readAsDataURL(this.editProductData.image);
    },
    hideNewProductModal: function hideNewProductModal() {
      this.$refs.ProductModal.hide();
    },
    showNewProductModal: function showNewProductModal() {
      this.$refs.ProductModal.show();
    },
    updateProduct: function () {
      var _updateProduct = _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee3() {
        var formData, response;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee3$(_context3) {
          while (1) {
            switch (_context3.prev = _context3.next) {
              case 0:
                formData = new FormData();
                formData.append('name', this.editProductData.product_name);
                formData.append('categoryID', this.editProductData.category_id);
                formData.append('image', this.editProductData.image);
                formData.append('id', this.editProductData.id);
                formData.append('_method', 'PUT');
                _context3.prev = 6;
                _context3.next = 9;
                return _Services_product_service__WEBPACK_IMPORTED_MODULE_1__.updateProduct(this.editProductData.id, formData);

              case 9:
                response = _context3.sent;
                this.Products.map(function (product) {
                  if (product.id == response.data.id) {
                    for (var key in response.data) {
                      product[key] = response.data[key];
                    }
                  }
                });
                console.log(this.Products);
                this.hideEditProductModal();
                this.flashMessage.success({
                  message: 'Product Updated Successfully!',
                  time: 5000,
                  blockClass: 'custom-block-class'
                });
                this.editCategoryData = {};
                _context3.next = 20;
                break;

              case 17:
                _context3.prev = 17;
                _context3.t0 = _context3["catch"](6);
                console.log('update called', _context3.t0);

              case 20:
              case "end":
                return _context3.stop();
            }
          }
        }, _callee3, this, [[6, 17]]);
      }));

      function updateProduct() {
        return _updateProduct.apply(this, arguments);
      }

      return updateProduct;
    }(),
    createProduct: function () {
      var _createProduct = _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee4() {
        var formData, response;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee4$(_context4) {
          while (1) {
            switch (_context4.prev = _context4.next) {
              case 0:
                this.errors = {};
                formData = new FormData();
                formData.append('category_id', this.ProductData.category_id);
                formData.append('name', this.ProductData.name);
                formData.append('image', this.ProductData.image);
                _context4.prev = 5;
                _context4.next = 8;
                return _Services_product_service__WEBPACK_IMPORTED_MODULE_1__.createProduct(formData);

              case 8:
                response = _context4.sent;
                this.Products.unshift(response.data);
                this.hideNewProductModal();
                this.flashMessage.success({
                  message: 'Product Added Successfully!',
                  time: 5000,
                  blockClass: 'custom-block-class'
                });
                this.ProductData = {
                  name: '',
                  image: ''
                };
                _context4.next = 24;
                break;

              case 15:
                _context4.prev = 15;
                _context4.t0 = _context4["catch"](5);
                _context4.t1 = _context4.t0.response.status;
                _context4.next = _context4.t1 === 422 ? 20 : 23;
                break;

              case 20:
                this.errors = _context4.t0.response.data.errors;
                console.log('err', this.errors);
                return _context4.abrupt("break", 24);

              case 23:
                return _context4.abrupt("break", 24);

              case 24:
              case "end":
                return _context4.stop();
            }
          }
        }, _callee4, this, [[5, 15]]);
      }));

      function createProduct() {
        return _createProduct.apply(this, arguments);
      }

      return createProduct;
    }(),
    deleteProduct: function () {
      var _deleteProduct = _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee5(Product) {
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee5$(_context5) {
          while (1) {
            switch (_context5.prev = _context5.next) {
              case 0:
                console.log(Product);

                if (!window.confirm("Are you sure you want to delete the ".concat(Product.product_name, " ?"))) {
                  _context5.next = 12;
                  break;
                }

                _context5.prev = 2;
                _context5.next = 5;
                return _Services_product_service__WEBPACK_IMPORTED_MODULE_1__.deleteProduct(Product.id);

              case 5:
                this.Products = this.Products.filter(function (obj) {
                  return obj.id != Product.id;
                });
                this.flashMessage.success({
                  message: 'Product Deleted Successfully!',
                  time: 5000,
                  blockClass: 'custom-block-class'
                });
                _context5.next = 12;
                break;

              case 9:
                _context5.prev = 9;
                _context5.t0 = _context5["catch"](2);
                this.flashMessage.success({
                  message: _context5.t0.response.data.message,
                  time: 5000,
                  blockClass: 'custom-block-class'
                });

              case 12:
              case "end":
                return _context5.stop();
            }
          }
        }, _callee5, this, [[2, 9]]);
      }));

      function deleteProduct(_x) {
        return _deleteProduct.apply(this, arguments);
      }

      return deleteProduct;
    }(),
    hideEditProductModal: function hideEditProductModal() {
      this.$refs.editProductModal.hide();
    },
    showEditProductModal: function showEditProductModal() {
      this.$refs.editProductModal.show();
    },
    findCategory: function findCategory(category_id) {
      var categoryName = '';
      this.categories.forEach(function (category) {
        console.log(category_id, category.id, category.name);

        if (category.id == category_id) {
          categoryName = category.name;
        }
      });
      return categoryName;
    },
    loadMore: function () {
      var _loadMore = _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee6() {
        var _this = this;

        var response;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee6$(_context6) {
          while (1) {
            switch (_context6.prev = _context6.next) {
              case 0:
                _context6.prev = 0;
                _context6.next = 3;
                return _Services_product_service__WEBPACK_IMPORTED_MODULE_1__.loadMore(this.nextPage);

              case 3:
                response = _context6.sent;

                if (response.data.current_page < response.data.last_page) {
                  this.moreExist = true;
                  this.nextPage = response.data.current_page + 1;
                } else {
                  this.moreExist = false;
                }

                response.data.data.forEach(function (data) {
                  _this.Products.push(data);
                });
                _context6.next = 11;
                break;

              case 8:
                _context6.prev = 8;
                _context6.t0 = _context6["catch"](0);
                this.flashMessage.success({
                  message: 'Some error occured during loading more categories',
                  time: 5000,
                  blockClass: 'custom-block-class'
                });

              case 11:
              case "end":
                return _context6.stop();
            }
          }
        }, _callee6, this, [[0, 8]]);
      }));

      function loadMore() {
        return _loadMore.apply(this, arguments);
      }

      return loadMore;
    }()
  }
});

/***/ }),

/***/ "./resources/js/Services/product_service.js":
/*!**************************************************!*\
  !*** ./resources/js/Services/product_service.js ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "createProduct": () => (/* binding */ createProduct),
/* harmony export */   "getCategories": () => (/* binding */ getCategories),
/* harmony export */   "loadProducts": () => (/* binding */ loadProducts),
/* harmony export */   "deleteProduct": () => (/* binding */ deleteProduct),
/* harmony export */   "updateProduct": () => (/* binding */ updateProduct),
/* harmony export */   "loadMore": () => (/* binding */ loadMore)
/* harmony export */ });
/* harmony import */ var _http_service__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./http_service */ "./resources/js/Services/http_service.js");

function createProduct(data) {
  return (0,_http_service__WEBPACK_IMPORTED_MODULE_0__.httpFile)().post('/products', data);
}
function getCategories() {
  return (0,_http_service__WEBPACK_IMPORTED_MODULE_0__.http)().get('/get_categories');
}
function loadProducts() {
  return (0,_http_service__WEBPACK_IMPORTED_MODULE_0__.http)().get('/products');
}
function deleteProduct(id) {
  return (0,_http_service__WEBPACK_IMPORTED_MODULE_0__.http)()["delete"]("/products/".concat(id));
}
function updateProduct(id, data) {
  return (0,_http_service__WEBPACK_IMPORTED_MODULE_0__.httpFile)().post("/products/".concat(id), data);
}
function loadMore(nextpage) {
  return (0,_http_service__WEBPACK_IMPORTED_MODULE_0__.http)().get("/products?page=".concat(nextpage));
}

/***/ }),

/***/ "./resources/js/views/Products.vue":
/*!*****************************************!*\
  !*** ./resources/js/views/Products.vue ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Products_vue_vue_type_template_id_eec6f8fa_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Products.vue?vue&type=template&id=eec6f8fa&scoped=true& */ "./resources/js/views/Products.vue?vue&type=template&id=eec6f8fa&scoped=true&");
/* harmony import */ var _Products_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Products.vue?vue&type=script&lang=js& */ "./resources/js/views/Products.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Products_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Products_vue_vue_type_template_id_eec6f8fa_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _Products_vue_vue_type_template_id_eec6f8fa_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "eec6f8fa",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/views/Products.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/views/Products.vue?vue&type=script&lang=js&":
/*!******************************************************************!*\
  !*** ./resources/js/views/Products.vue?vue&type=script&lang=js& ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Products_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Products.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Products.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Products_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/views/Products.vue?vue&type=template&id=eec6f8fa&scoped=true&":
/*!************************************************************************************!*\
  !*** ./resources/js/views/Products.vue?vue&type=template&id=eec6f8fa&scoped=true& ***!
  \************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Products_vue_vue_type_template_id_eec6f8fa_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Products_vue_vue_type_template_id_eec6f8fa_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Products_vue_vue_type_template_id_eec6f8fa_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Products.vue?vue&type=template&id=eec6f8fa&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Products.vue?vue&type=template&id=eec6f8fa&scoped=true&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Products.vue?vue&type=template&id=eec6f8fa&scoped=true&":
/*!***************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Products.vue?vue&type=template&id=eec6f8fa&scoped=true& ***!
  \***************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { staticClass: "container-fluid px-4" },
    [
      _c("ol", { staticClass: "breadcrumb mt-4" }, [
        _c(
          "li",
          { staticClass: "breadcrumb-item active" },
          [
            _c("router-link", { attrs: { to: "/" } }, [
              _vm._v("\n                    Dashboard\n                "),
            ]),
          ],
          1
        ),
        _vm._v(" "),
        _c("li", { staticClass: "breadcrumb-item" }, [
          _vm._v("\n                Products\n            "),
        ]),
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "card mb-3" }, [
        _c("div", { staticClass: "card-header d-flex" }, [
          _vm._m(0),
          _vm._v(" "),
          _c(
            "button",
            {
              staticClass: "btn btn-primary btn-sm ml-auto",
              on: { click: _vm.showNewProductModal },
            },
            [_c("span", { staticClass: "fa fa-plus" }), _vm._v(" Create New")]
          ),
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "card-body" }, [
          _c("table", { staticClass: "table" }, [
            _vm._m(1),
            _vm._v(" "),
            _c(
              "tbody",
              _vm._l(_vm.Products, function (Product, index) {
                return _c("tr", { key: index }, [
                  _c("td", [_vm._v(_vm._s(index + 1))]),
                  _vm._v(" "),
                  _c("td", [
                    _vm._v(_vm._s(_vm.findCategory(Product.category_id))),
                  ]),
                  _vm._v(" "),
                  _c("td", [_vm._v(_vm._s(Product.product_name))]),
                  _vm._v(" "),
                  _c("td", [
                    _c("img", {
                      staticClass: "img-thumbnail",
                      attrs: {
                        src:
                          _vm.$store.state.serverPath +
                          "assets/uploads/product/" +
                          Product.image,
                        alt: Product.name,
                      },
                    }),
                  ]),
                  _vm._v(" "),
                  _c("td", [
                    _c(
                      "button",
                      {
                        staticClass: "btn btn-primary btn-sm",
                        on: {
                          click: function ($event) {
                            return _vm.editProduct(Product)
                          },
                        },
                      },
                      [_c("span", { staticClass: "fa fa-edit" })]
                    ),
                    _vm._v(" "),
                    _c(
                      "button",
                      {
                        staticClass: "btn btn-danger btn-sm",
                        on: {
                          click: function ($event) {
                            return _vm.deleteProduct(Product)
                          },
                        },
                      },
                      [_c("span", { staticClass: "fa fa-trash" })]
                    ),
                  ]),
                ])
              }),
              0
            ),
          ]),
          _vm._v(" "),
          _c(
            "div",
            {
              directives: [
                {
                  name: "show",
                  rawName: "v-show",
                  value: _vm.moreExist,
                  expression: "moreExist",
                },
              ],
              staticClass: "text-center",
            },
            [
              _c(
                "button",
                {
                  staticClass: "btn btn-primary btn-sm",
                  on: { click: _vm.loadMore },
                },
                [
                  _c("span", { staticClass: "fa fa-arrow-down" }),
                  _vm._v(" Load More"),
                ]
              ),
            ]
          ),
        ]),
      ]),
      _vm._v(" "),
      _c(
        "b-modal",
        {
          ref: "ProductModal",
          attrs: { "hide-footer": "", title: "Add New Product" },
        },
        [
          _c("div", { staticClass: "d-block" }, [
            _c(
              "form",
              {
                on: {
                  submit: function ($event) {
                    $event.preventDefault()
                    return _vm.createProduct.apply(null, arguments)
                  },
                },
              },
              [
                _c("div", { staticClass: "mb-3" }, [
                  _c(
                    "label",
                    {
                      staticClass: "form-label",
                      attrs: { for: "category_id" },
                    },
                    [_vm._v("Category")]
                  ),
                  _vm._v(" "),
                  _c(
                    "select",
                    {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.ProductData.category_id,
                          expression: "ProductData.category_id",
                        },
                      ],
                      staticClass: "form-control",
                      attrs: { id: "category_id" },
                      on: {
                        change: function ($event) {
                          var $$selectedVal = Array.prototype.filter
                            .call($event.target.options, function (o) {
                              return o.selected
                            })
                            .map(function (o) {
                              var val = "_value" in o ? o._value : o.value
                              return val
                            })
                          _vm.$set(
                            _vm.ProductData,
                            "category_id",
                            $event.target.multiple
                              ? $$selectedVal
                              : $$selectedVal[0]
                          )
                        },
                      },
                    },
                    [
                      _c("option", { attrs: { value: "" } }, [
                        _vm._v("Choose Category"),
                      ]),
                      _vm._v(" "),
                      _vm._l(_vm.categories, function (category, index) {
                        return _c(
                          "option",
                          { key: index, domProps: { value: category.id } },
                          [_vm._v(_vm._s(category.name))]
                        )
                      }),
                    ],
                    2
                  ),
                  _vm._v(" "),
                  _vm.errors.category_id
                    ? _c("div", { staticClass: "invalid-feedback" }, [
                        _vm._v(_vm._s(_vm.errors.category_id[0])),
                      ])
                    : _vm._e(),
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "mb-3" }, [
                  _c(
                    "label",
                    { staticClass: "form-label", attrs: { for: "name" } },
                    [_vm._v("Enter Product Name")]
                  ),
                  _vm._v(" "),
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.ProductData.name,
                        expression: "ProductData.name",
                      },
                    ],
                    staticClass: "form-control",
                    attrs: {
                      type: "text",
                      id: "product_name",
                      placeholder: "Enter Product Name",
                    },
                    domProps: { value: _vm.ProductData.name },
                    on: {
                      input: function ($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(_vm.ProductData, "name", $event.target.value)
                      },
                    },
                  }),
                  _vm._v(" "),
                  _vm.errors.name
                    ? _c("div", { staticClass: "invalid-feedback" }, [
                        _vm._v(_vm._s(_vm.errors.name[0])),
                      ])
                    : _vm._e(),
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "mb-3" }, [
                  _c(
                    "label",
                    { staticClass: "form-label", attrs: { for: "image" } },
                    [_vm._v("Choose an Image")]
                  ),
                  _vm._v(" "),
                  _vm.ProductData.image.name
                    ? _c("div", [
                        _c("img", {
                          ref: "newProductImageDisplay",
                          staticClass: "img-thumbnail",
                        }),
                      ])
                    : _vm._e(),
                  _vm._v(" "),
                  _c("input", {
                    ref: "newProductImage",
                    staticClass: "form-control",
                    attrs: { type: "file", id: "image" },
                    on: { change: _vm.attachImage },
                  }),
                  _vm._v(" "),
                  _vm.errors.image
                    ? _c("div", { staticClass: "invalid-feedback" }, [
                        _vm._v(_vm._s(_vm.errors.image[0])),
                      ])
                    : _vm._e(),
                ]),
                _vm._v(" "),
                _c("hr"),
                _vm._v(" "),
                _c("div", { staticClass: "text-right" }, [
                  _c(
                    "button",
                    {
                      staticClass: "btn btn-default",
                      attrs: { type: "button" },
                      on: { click: _vm.hideNewProductModal },
                    },
                    [_vm._v(" Cancel")]
                  ),
                  _vm._v(" "),
                  _c(
                    "button",
                    {
                      staticClass: "btn btn-primary",
                      attrs: { type: "submit" },
                    },
                    [
                      _c("span", { staticClass: "fa fa-check" }),
                      _vm._v(" Save"),
                    ]
                  ),
                ]),
              ]
            ),
          ]),
        ]
      ),
      _vm._v(" "),
      _c(
        "b-modal",
        {
          ref: "editProductModal",
          attrs: { "hide-footer": "", title: "Edit Product" },
        },
        [
          _c("div", { staticClass: "d-block" }, [
            _c(
              "form",
              {
                on: {
                  submit: function ($event) {
                    $event.preventDefault()
                    return _vm.updateProduct.apply(null, arguments)
                  },
                },
              },
              [
                _c("div", { staticClass: "mb-3" }, [
                  _c(
                    "label",
                    {
                      staticClass: "form-label",
                      attrs: { for: "category_id" },
                    },
                    [_vm._v("Category")]
                  ),
                  _vm._v(" "),
                  _c(
                    "select",
                    {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.editProductData.category_id,
                          expression: "editProductData.category_id",
                        },
                      ],
                      staticClass: "form-control",
                      attrs: { id: "category_id", name: "category_id" },
                      on: {
                        change: function ($event) {
                          var $$selectedVal = Array.prototype.filter
                            .call($event.target.options, function (o) {
                              return o.selected
                            })
                            .map(function (o) {
                              var val = "_value" in o ? o._value : o.value
                              return val
                            })
                          _vm.$set(
                            _vm.editProductData,
                            "category_id",
                            $event.target.multiple
                              ? $$selectedVal
                              : $$selectedVal[0]
                          )
                        },
                      },
                    },
                    [
                      _c("option", { attrs: { value: "" } }, [
                        _vm._v("Choose Category"),
                      ]),
                      _vm._v(" "),
                      _vm._l(_vm.categories, function (category, index) {
                        return _c(
                          "option",
                          { key: index, domProps: { value: category.id } },
                          [_vm._v(_vm._s(category.name))]
                        )
                      }),
                    ],
                    2
                  ),
                  _vm._v(" "),
                  _vm.errors.category_id
                    ? _c("div", { staticClass: "invalid-feedback" }, [
                        _vm._v(_vm._s(_vm.errors.category_id[0])),
                      ])
                    : _vm._e(),
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "mb-3" }, [
                  _c(
                    "label",
                    { staticClass: "form-label", attrs: { for: "name" } },
                    [_vm._v("Enter Product Name")]
                  ),
                  _vm._v(" "),
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.editProductData.product_name,
                        expression: "editProductData.product_name",
                      },
                    ],
                    staticClass: "form-control",
                    attrs: {
                      type: "text",
                      id: "name",
                      placeholder: "Enter Product Name",
                    },
                    domProps: { value: _vm.editProductData.product_name },
                    on: {
                      input: function ($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(
                          _vm.editProductData,
                          "product_name",
                          $event.target.value
                        )
                      },
                    },
                  }),
                  _vm._v(" "),
                  _vm.errors.name
                    ? _c("div", { staticClass: "invalid-feedback" }, [
                        _vm._v(_vm._s(_vm.errors.name[0])),
                      ])
                    : _vm._e(),
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "mb-3" }, [
                  _c(
                    "label",
                    { staticClass: "form-label", attrs: { for: "image" } },
                    [_vm._v("Choose an Image")]
                  ),
                  _vm._v(" "),
                  _c("div", [
                    _c("img", {
                      ref: "editProductImageDisplay",
                      staticClass: "img-thumbnail",
                      attrs: {
                        src:
                          _vm.$store.state.serverPath +
                          "assets/uploads/product/" +
                          _vm.editProductData.image,
                        alt: _vm.editProductData.name,
                      },
                    }),
                  ]),
                  _vm._v(" "),
                  _c("input", {
                    ref: "editProductImage",
                    staticClass: "form-control",
                    attrs: { type: "file", id: "image" },
                    on: { change: _vm.editAttachImage },
                  }),
                  _vm._v(" "),
                  _vm.errors.image
                    ? _c("div", { staticClass: "invalid-feedback" }, [
                        _vm._v(_vm._s(_vm.errors.image[0])),
                      ])
                    : _vm._e(),
                ]),
                _vm._v(" "),
                _c("hr"),
                _vm._v(" "),
                _c("div", { staticClass: "text-right" }, [
                  _c(
                    "button",
                    {
                      staticClass: "btn btn-default",
                      attrs: { type: "button" },
                      on: { click: _vm.hideEditProductModal },
                    },
                    [_vm._v(" Cancel")]
                  ),
                  _vm._v(" "),
                  _c(
                    "button",
                    {
                      staticClass: "btn btn-primary",
                      attrs: { type: "submit" },
                    },
                    [
                      _c("span", { staticClass: "fa fa-check" }),
                      _vm._v(" Update"),
                    ]
                  ),
                ]),
              ]
            ),
          ]),
        ]
      ),
    ],
    1
  )
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("span", [
      _c("i", { staticClass: "fas fa-chart-area" }),
      _vm._v("\n                    Products Management\n              "),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("thead", [
      _c("tr", [
        _c("td", [_vm._v("#")]),
        _vm._v(" "),
        _c("td", [_vm._v("Category")]),
        _vm._v(" "),
        _c("td", [_vm._v("Product Name")]),
        _vm._v(" "),
        _c("td", [_vm._v("Image")]),
        _vm._v(" "),
        _c("td", [_vm._v("Action")]),
      ]),
    ])
  },
]
render._withStripped = true



/***/ })

}]);