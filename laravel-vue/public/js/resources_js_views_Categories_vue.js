"use strict";
(self["webpackChunklaravel_vue"] = self["webpackChunklaravel_vue"] || []).push([["resources_js_views_Categories_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Categories.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Categories.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "./node_modules/@babel/runtime/regenerator/index.js");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _Services_category_service__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../Services/category_service */ "./resources/js/Services/category_service.js");


function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }

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
  name: "Categories",
  data: function data() {
    return {
      categories: [],
      categoryData: {
        name: '',
        image: ''
      },
      editCategoryData: {},
      errors: {}
    };
  },
  mounted: function mounted() {
    this.loadCategories();
  },
  methods: {
    editCategory: function editCategory(category) {
      this.editCategoryData = category;
      this.showEditCategoryModal();
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
                return _Services_category_service__WEBPACK_IMPORTED_MODULE_1__.loadCategories();

              case 3:
                response = _context.sent;
                this.categories = response.data.data;
                console.log(response.data.data);
                _context.next = 12;
                break;

              case 8:
                _context.prev = 8;
                _context.t0 = _context["catch"](0);
                console.log(_context.t0);
                this.flashMessage.success({
                  message: 'Some Error Occured!, Please Refresh!',
                  time: 5000
                });

              case 12:
              case "end":
                return _context.stop();
            }
          }
        }, _callee, this, [[0, 8]]);
      }));

      function loadCategories() {
        return _loadCategories.apply(this, arguments);
      }

      return loadCategories;
    }(),
    attachImage: function attachImage() {
      //to use file reader
      this.categoryData.image = this.$refs.newCategoryImage.files[0];
      var reader = new FileReader();
      reader.addEventListener('load', function () {
        this.$refs.newCategoryImageDisplay.src = reader.result;
      }.bind(this), false);
      reader.readAsDataURL(this.categoryData.image);
    },
    editAttachImage: function editAttachImage() {
      //to use file reader
      this.editCategoryData.image = this.$refs.editCategoryImage.files[0];
      var reader = new FileReader();
      reader.addEventListener('load', function () {
        this.$refs.editCategoryImageDisplay.src = reader.result;
      }.bind(this), false);
      reader.readAsDataURL(this.editCategoryData.image);
    },
    hideNewCategoryModal: function hideNewCategoryModal() {
      this.$refs.categoryModal.hide();
    },
    showNewCategoryModal: function showNewCategoryModal() {
      this.$refs.categoryModal.show();
    },
    updateCategory: function () {
      var _updateCategory = _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee2() {
        var formData, response;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee2$(_context2) {
          while (1) {
            switch (_context2.prev = _context2.next) {
              case 0:
                formData = new FormData();
                formData.append('name', this.editCategoryData.name);
                formData.append('image', this.editCategoryData.image);
                formData.append('_method', 'PUT');
                _context2.prev = 4;
                _context2.next = 7;
                return _Services_category_service__WEBPACK_IMPORTED_MODULE_1__.updateCategory(this.editCategoryData.id, formData);

              case 7:
                response = _context2.sent;
                _context2.next = 13;
                break;

              case 10:
                _context2.prev = 10;
                _context2.t0 = _context2["catch"](4);
                console.log('update called', _context2.t0);

              case 13:
              case "end":
                return _context2.stop();
            }
          }
        }, _callee2, this, [[4, 10]]);
      }));

      function updateCategory() {
        return _updateCategory.apply(this, arguments);
      }

      return updateCategory;
    }(),
    createCategory: function () {
      var _createCategory = _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee3() {
        var formData, response;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee3$(_context3) {
          while (1) {
            switch (_context3.prev = _context3.next) {
              case 0:
                formData = new FormData();
                formData.append('name', this.categoryData.name);
                formData.append('image', this.categoryData.image);
                _context3.prev = 3;
                _context3.next = 6;
                return _Services_category_service__WEBPACK_IMPORTED_MODULE_1__.createCategory(formData);

              case 6:
                response = _context3.sent;
                this.categories.unshift(response.data);
                this.hideNewCategoryModal();
                this.flashMessage.success({
                  message: 'Category Added Successfully!',
                  time: 5000
                });
                this.categoryData = {
                  name: '',
                  image: ''
                };
                _context3.next = 21;
                break;

              case 13:
                _context3.prev = 13;
                _context3.t0 = _context3["catch"](3);
                _context3.t1 = _context3.t0.response.status;
                _context3.next = _context3.t1 === 422 ? 18 : 20;
                break;

              case 18:
                this.errors = _context3.t0.response.data.errors;
                return _context3.abrupt("break", 21);

              case 20:
                return _context3.abrupt("break", 21);

              case 21:
              case "end":
                return _context3.stop();
            }
          }
        }, _callee3, this, [[3, 13]]);
      }));

      function createCategory() {
        return _createCategory.apply(this, arguments);
      }

      return createCategory;
    }(),
    deleteCategory: function () {
      var _deleteCategory = _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee4(category) {
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee4$(_context4) {
          while (1) {
            switch (_context4.prev = _context4.next) {
              case 0:
                console.log(category);

                if (!window.confirm("Are you sure you want to delete the ".concat(category.name, " ?"))) {
                  _context4.next = 12;
                  break;
                }

                _context4.prev = 2;
                _context4.next = 5;
                return _Services_category_service__WEBPACK_IMPORTED_MODULE_1__.deleteCategory(category.id);

              case 5:
                this.categories = this.categories.filter(function (obj) {
                  return obj.id != category.id;
                });
                this.flashMessage.success({
                  message: 'Category Deleted Successfully!',
                  time: 5000
                });
                _context4.next = 12;
                break;

              case 9:
                _context4.prev = 9;
                _context4.t0 = _context4["catch"](2);
                this.flashMessage.success({
                  message: _context4.t0.response.data.message,
                  time: 5000
                });

              case 12:
              case "end":
                return _context4.stop();
            }
          }
        }, _callee4, this, [[2, 9]]);
      }));

      function deleteCategory(_x) {
        return _deleteCategory.apply(this, arguments);
      }

      return deleteCategory;
    }(),
    hideEditCategoryModal: function hideEditCategoryModal() {
      this.$refs.editCategoryModal.hide();
    },
    showEditCategoryModal: function showEditCategoryModal() {
      this.$refs.editCategoryModal.show();
    }
  }
});

/***/ }),

/***/ "./resources/js/Services/category_service.js":
/*!***************************************************!*\
  !*** ./resources/js/Services/category_service.js ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "createCategory": () => (/* binding */ createCategory),
/* harmony export */   "loadCategories": () => (/* binding */ loadCategories),
/* harmony export */   "deleteCategory": () => (/* binding */ deleteCategory),
/* harmony export */   "updateCategory": () => (/* binding */ updateCategory),
/* harmony export */   "loadMore": () => (/* binding */ loadMore)
/* harmony export */ });
/* harmony import */ var _http_service__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./http_service */ "./resources/js/Services/http_service.js");

function createCategory(data) {
  return (0,_http_service__WEBPACK_IMPORTED_MODULE_0__.httpFile)().post('/categories', data);
}
function loadCategories() {
  return (0,_http_service__WEBPACK_IMPORTED_MODULE_0__.http)().get('/categories');
}
function deleteCategory(id) {
  return (0,_http_service__WEBPACK_IMPORTED_MODULE_0__.http)()["delete"]("/categories/".concat(id));
}
function updateCategory(id, data) {
  return (0,_http_service__WEBPACK_IMPORTED_MODULE_0__.httpFile)().post("/categories/".concat(id), data);
}
function loadMore(nextpage) {
  return (0,_http_service__WEBPACK_IMPORTED_MODULE_0__.http)().get("/categories?page=".concat(nextpage));
}

/***/ }),

/***/ "./resources/js/views/Categories.vue":
/*!*******************************************!*\
  !*** ./resources/js/views/Categories.vue ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Categories_vue_vue_type_template_id_53f0967b_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Categories.vue?vue&type=template&id=53f0967b&scoped=true& */ "./resources/js/views/Categories.vue?vue&type=template&id=53f0967b&scoped=true&");
/* harmony import */ var _Categories_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Categories.vue?vue&type=script&lang=js& */ "./resources/js/views/Categories.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Categories_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Categories_vue_vue_type_template_id_53f0967b_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _Categories_vue_vue_type_template_id_53f0967b_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "53f0967b",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/views/Categories.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/views/Categories.vue?vue&type=script&lang=js&":
/*!********************************************************************!*\
  !*** ./resources/js/views/Categories.vue?vue&type=script&lang=js& ***!
  \********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Categories_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Categories.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Categories.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Categories_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/views/Categories.vue?vue&type=template&id=53f0967b&scoped=true&":
/*!**************************************************************************************!*\
  !*** ./resources/js/views/Categories.vue?vue&type=template&id=53f0967b&scoped=true& ***!
  \**************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Categories_vue_vue_type_template_id_53f0967b_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Categories_vue_vue_type_template_id_53f0967b_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Categories_vue_vue_type_template_id_53f0967b_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Categories.vue?vue&type=template&id=53f0967b&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Categories.vue?vue&type=template&id=53f0967b&scoped=true&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Categories.vue?vue&type=template&id=53f0967b&scoped=true&":
/*!*****************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Categories.vue?vue&type=template&id=53f0967b&scoped=true& ***!
  \*****************************************************************************************************************************************************************************************************************************/
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
              _vm._v("\n                Dashboard\n            "),
            ]),
          ],
          1
        ),
        _vm._v(" "),
        _c("li", { staticClass: "breadcrumb-item" }, [
          _vm._v("\n            Categories\n        "),
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
              on: { click: _vm.showNewCategoryModal },
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
              _vm._l(_vm.categories, function (category, index) {
                return _c("tr", { key: index }, [
                  _c("td", [_vm._v(_vm._s(index + 1))]),
                  _vm._v(" "),
                  _c("td", [_vm._v(_vm._s(category.name))]),
                  _vm._v(" "),
                  _c("td", [
                    _c("img", {
                      staticClass: "img-thumbnail",
                      attrs: {
                        src:
                          _vm.$store.state.serverPath +
                          "assets/uploads/category/" +
                          category.image,
                        alt: category.name,
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
                            return _vm.editCategory(category)
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
                            return _vm.deleteCategory(category)
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
        ]),
      ]),
      _vm._v(" "),
      _c(
        "b-modal",
        {
          ref: "categoryModal",
          attrs: { "hide-footer": "", title: "Add New Category" },
        },
        [
          _c("div", { staticClass: "d-block" }, [
            _c(
              "form",
              {
                on: {
                  submit: function ($event) {
                    $event.preventDefault()
                    return _vm.createCategory.apply(null, arguments)
                  },
                },
              },
              [
                _c("div", { staticClass: "mb-3" }, [
                  _c(
                    "label",
                    { staticClass: "form-label", attrs: { for: "name" } },
                    [_vm._v("Enter Name")]
                  ),
                  _vm._v(" "),
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.categoryData.name,
                        expression: "categoryData.name",
                      },
                    ],
                    staticClass: "form-control",
                    attrs: {
                      type: "text",
                      id: "name",
                      placeholder: "Enter Name",
                    },
                    domProps: { value: _vm.categoryData.name },
                    on: {
                      input: function ($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(_vm.categoryData, "name", $event.target.value)
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
                  _vm.categoryData.image.name
                    ? _c("div", [
                        _c("img", {
                          ref: "newCategoryImageDisplay",
                          staticClass: "img-thumbnail",
                        }),
                      ])
                    : _vm._e(),
                  _vm._v(" "),
                  _c("input", {
                    ref: "newCategoryImage",
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
                      on: { click: _vm.hideNewCategoryModal },
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
          ref: "editCategoryModal",
          attrs: { "hide-footer": "", title: "Edit Category" },
        },
        [
          _c("div", { staticClass: "d-block" }, [
            _c(
              "form",
              {
                on: {
                  submit: function ($event) {
                    $event.preventDefault()
                    return _vm.updateCategory.apply(null, arguments)
                  },
                },
              },
              [
                _c("div", { staticClass: "mb-3" }, [
                  _c(
                    "label",
                    { staticClass: "form-label", attrs: { for: "name" } },
                    [_vm._v("Enter Name")]
                  ),
                  _vm._v(" "),
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.editCategoryData.name,
                        expression: "editCategoryData.name",
                      },
                    ],
                    staticClass: "form-control",
                    attrs: {
                      type: "text",
                      id: "name",
                      placeholder: "Enter Name",
                    },
                    domProps: { value: _vm.editCategoryData.name },
                    on: {
                      input: function ($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(
                          _vm.editCategoryData,
                          "name",
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
                      ref: "editCategoryImageDisplay",
                      staticClass: "img-thumbnail",
                      attrs: {
                        src:
                          _vm.$store.state.serverPath +
                          "assets/uploads/category/" +
                          _vm.editCategoryData.image,
                        alt: _vm.editCategoryData.name,
                      },
                    }),
                  ]),
                  _vm._v(" "),
                  _c("input", {
                    ref: "editCategoryImage",
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
                      on: { click: _vm.hideEditCategoryModal },
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
      _vm._v("\n                Categories Management\n          "),
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
        _c("td", [_vm._v("Name")]),
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