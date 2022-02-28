(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[0],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/admin/pages/category.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/admin/pages/category.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "./node_modules/@babel/runtime/regenerator/index.js");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);


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
/* harmony default export */ __webpack_exports__["default"] = ({
  name: "category",
  data: function data() {
    return {
      categoryData: {
        iconImage: '',
        categoryName: ''
      },
      categories: [],
      headers: [{
        name: '#'
      }, {
        name: 'Icon Image'
      }, {
        name: 'Category Name'
      }, {
        name: 'Created At'
      }, {
        name: 'Actions'
      }],
      isAdding: false,
      editCategoryData: {
        categoryName: '',
        iconImage: ''
      },
      showDeleteModal: false,
      deleteItem: {},
      deleteIndex: -1,
      modalLoading: false,
      token: '',
      customFlags: {
        isAdd: false,
        AddModal: false,
        isEdit: false,
        EditModal: false,
        index: -1
      }
    };
  },
  methods: {
    addData: function addData() {
      this.customFlags.isAdd = true;
      this.customFlags.AddModal = true;
      /*
                  if (this.categoryData.categoryName.trim() == '') {
                      return this.error('Category Name is required');
                  }
                    const res =  await this.callApi('post','/app/create_category',this.categoryData);
                    if (res.status == 201) {
                       // this.tags.unshift(res.data);
                        this.success('Category has been added successfully!');
                        this.AddModal = false;
                    } else{
                        if (res.status == 422) {
                            if (res.data.errors.categoryName) {
                                this.info(res.data.errors.categoryName[0]);
                            }
                         } else {
                              this.error('Some error occured');
                         }
      
                    }
                    this.categoryData = {}; */
    },
    editData: function editData() {
      this.customFlags.isEdit = true;
      this.customFlags.EditModal = true;
      /*   if (this.editCategoryData.categoryName.trim() == '') {
            return this.error('Category Name is required');
        }
          const res =  await this.callApi('post','/app/edit_category',this.editCategoryData);
          if (res.status == 200) {
              this.categories[this.index].categoryName=this.editCategoryData.categoryName;
              this.success('Category has been edited successfully!');
              this.EditModal = false;
          } else{
              if (res.status == 422) {
                  if (res.data.errors.categoryName) {
                      this.info(res.data.errors.categoryName[0]);
                  }
               } else {
                    this.error('Some error occured');
               }
           } */
    },
    saveData: function saveData() {
      var _this = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee() {
        var res, _res;

        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                if (!_this.customFlags.isAdd) {
                  _context.next = 12;
                  break;
                }

                if (!(_this.categoryData.categoryName.trim() == '')) {
                  _context.next = 3;
                  break;
                }

                return _context.abrupt("return", _this.error('Category Name is required'));

              case 3:
                if (!(_this.categoryData.iconImage.trim() == '')) {
                  _context.next = 5;
                  break;
                }

                return _context.abrupt("return", _this.error('Icon image is required'));

              case 5:
                _context.next = 7;
                return _this.callApi('post', '/app/create_category', _this.categoryData);

              case 7:
                res = _context.sent;

                if (res.status == 201) {
                  _this.categories.unshift(res.data);

                  _this.success('Category has been added successfully!');

                  _this.categoryData.iconImage = '';
                } else {
                  if (res.status == 422) {
                    if (res.data.errors.categoryName) {
                      _this.info(res.data.errors.categoryName[0]);
                    }

                    if (res.data.errors.iconImage) {
                      _this.info(res.data.errors.iconImage[0]);
                    }
                  } else {
                    _this.error('Some error occured');
                  }
                }

                _this.categoryData = {};
                _this.customFlags.isAdd = false;
                _this.customFlags.AddModal = false;

              case 12:
                if (!_this.customFlags.isEdit) {
                  _context.next = 22;
                  break;
                }

                if (!(_this.editCategoryData.categoryName.trim() == '')) {
                  _context.next = 15;
                  break;
                }

                return _context.abrupt("return", _this.error('Category Name is required'));

              case 15:
                _context.next = 17;
                return _this.callApi('post', '/app/edit_category', _this.editCategoryData);

              case 17:
                _res = _context.sent;

                if (_res.status == 200) {
                  _this.categories[_this.customFlags.index].categoryName = _this.editCategoryData.categoryName;

                  _this.success('Category has been edited successfully!');
                } else {
                  if (_res.status == 422) {
                    if (_res.data.errors.categoryName) {
                      _this.info(_res.data.errors.categoryName[0]);
                    }
                  } else {
                    _this.error('Some error occured');
                  }
                }

                _this.customFlags.isEdit = false;
                _this.customFlags.EditModal = false;
                _this.editCategoryData = {};

              case 22:
              case "end":
                return _context.stop();
            }
          }
        }, _callee);
      }))();
    },
    showEditModal: function showEditModal(data, index) {
      var obj = {
        id: data.id,
        categoryName: data.category_name
      };
      this.editCategoryData = obj;
      this.customFlags.EditModal = true;
      this.customFlags.index = index;
    },
    showDeletingModal: function showDeletingModal(tag, index) {
      this.deleteItem = tag;
      this.deleteIndex = index;
      this.showDeleteModal = true;
    },
    deleteTag: function deleteTag() {
      var _this2 = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee2() {
        var res;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee2$(_context2) {
          while (1) {
            switch (_context2.prev = _context2.next) {
              case 0:
                _this2.modalLoading = true;
                _context2.next = 3;
                return _this2.callApi('post', 'app/delete_category', _this2.deleteItem);

              case 3:
                res = _context2.sent;

                if (res.status == 200) {
                  _this2.categories.splice(_this2.deleteIndex, 1);

                  _this2.success('Tag has been deleted successfully!');
                } else {
                  _this2.error();
                }

                _this2.modalLoading = false;
                _this2.showDeleteModal = false;

              case 7:
              case "end":
                return _context2.stop();
            }
          }
        }, _callee2);
      }))();
    },
    handleSuccess: function handleSuccess(res, file) {
      this.categoryData.iconImage = res;
    },
    handleFormatError: function handleFormatError(file) {
      this.$Notice.warning({
        title: 'The file format is incorrect',
        desc: 'File format of ' + file.name + ' is incorrect, please select jpg or png.'
      });
    },
    handleMaxSize: function handleMaxSize(file) {
      this.$Notice.warning({
        title: 'Exceeding file size limit',
        desc: 'File  ' + file.name + ' is too large, no more than 2M.'
      });
    },
    handleError: function handleError() {
      this.$Notice.warning({
        title: 'The file format is incorrect',
        desc: "".concat(file.errors.file.length ? file.errors.file[0] : 'Something went wrong!')
      });
    },
    deleteImage: function deleteImage() {
      var _this3 = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee3() {
        var image, res;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee3$(_context3) {
          while (1) {
            switch (_context3.prev = _context3.next) {
              case 0:
                image = _this3.categoryData.iconImage;
                _this3.categoryData.iconImage = '';

                _this3.$refs.uploads.clearFiles();

                _context3.next = 5;
                return _this3.callApi('post', 'app/delete_image', {
                  imageName: image
                });

              case 5:
                res = _context3.sent;

                if (res.status != 200) {
                  _this3.categoryData.iconImage = image;

                  _this3.error();
                }

              case 7:
              case "end":
                return _context3.stop();
            }
          }
        }, _callee3);
      }))();
    }
  },
  created: function created() {
    var _this4 = this;

    return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee4() {
      var res;
      return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee4$(_context4) {
        while (1) {
          switch (_context4.prev = _context4.next) {
            case 0:
              console.log(_this4.headers);
              _this4.token = window.Laravel.csrfToken;
              _context4.next = 4;
              return _this4.callApi('get', '/app/get_category');

            case 4:
              res = _context4.sent;

              if (res.status == 200) {
                _this4.categories = res.data;
              }

            case 6:
            case "end":
              return _context4.stop();
          }
        }
      }, _callee4);
    }))();
  }
});

/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/admin/pages/category.vue?vue&type=style&index=0&lang=css&":
/*!***************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/admin/pages/category.vue?vue&type=style&index=0&lang=css& ***!
  \***************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.space{\n    margin-top: 10px;\n    margin-bottom: 10px;\n}\n.image_thumb{\n    width: 50px;\n}\n.demo-upload-list{\n        display: inline-block;\n        width: 60px;\n        height: 60px;\n        text-align: center;\n        line-height: 60px;\n        border: 1px solid transparent;\n        border-radius: 4px;\n        overflow: hidden;\n        background: #fff;\n        position: relative;\n        box-shadow: 0 1px 1px rgba(0,0,0,.2);\n        margin-right: 4px;\n}\n.demo-upload-list img{\n        width: 100%;\n        height: 100%;\n}\n.demo-upload-list-cover{\n        display: none;\n        position: absolute;\n        top: 0;\n        bottom: 0;\n        left: 0;\n        right: 0;\n        background: rgba(0,0,0,.6);\n}\n.demo-upload-list:hover .demo-upload-list-cover{\n        display: block;\n}\n.demo-upload-list-cover i{\n        color: #fff;\n        font-size: 20px;\n        cursor: pointer;\n        margin: 0 2px;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/admin/pages/category.vue?vue&type=style&index=0&lang=css&":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/admin/pages/category.vue?vue&type=style&index=0&lang=css& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader??ref--6-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--6-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./category.vue?vue&type=style&index=0&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/admin/pages/category.vue?vue&type=style&index=0&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/admin/pages/category.vue?vue&type=template&id=525752b2&":
/*!************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/admin/pages/category.vue?vue&type=template&id=525752b2& ***!
  \************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [
    _vm._m(0),
    _vm._v(" "),
    _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "col-xl-12" }, [
        _c(
          "div",
          { staticClass: "card" },
          [
            _c(
              "div",
              { staticClass: "card-header" },
              [
                _c(
                  "Button",
                  { on: { click: _vm.addData } },
                  [
                    _c("Icon", { attrs: { type: "md-add" } }),
                    _vm._v(" Add Category"),
                  ],
                  1
                ),
              ],
              1
            ),
            _vm._v(" "),
            _c("div", { staticClass: "card-body table-border-style" }, [
              _c("div", { staticClass: "table-responsive" }, [
                _c("table", { staticClass: "table" }, [
                  _c("thead", [
                    _c(
                      "tr",
                      _vm._l(_vm.headers, function (header, indx) {
                        return _c("th", { key: indx }, [
                          _vm._v(_vm._s(header.name)),
                        ])
                      }),
                      0
                    ),
                  ]),
                  _vm._v(" "),
                  _c(
                    "tbody",
                    [
                      _vm._l(_vm.categories, function (category, i) {
                        return _c("tr", { key: i }, [
                          _c("td", [_vm._v(_vm._s(i + 1))]),
                          _vm._v(" "),
                          _c("td", [_vm._v(_vm._s(category.category_name))]),
                          _vm._v(" "),
                          _c("td", [
                            _c("img", {
                              attrs: {
                                src: category.iconImage,
                                alt: "",
                                height: "50px",
                                width: "50px",
                              },
                            }),
                          ]),
                          _vm._v(" "),
                          _c("td", [_vm._v(_vm._s(category.created_at))]),
                          _vm._v(" "),
                          _c(
                            "td",
                            [
                              _c(
                                "Button",
                                {
                                  attrs: { type: "info", size: "small" },
                                  on: {
                                    click: function ($event) {
                                      return _vm.showEditModal(category, i)
                                    },
                                  },
                                },
                                [_vm._v("Edit")]
                              ),
                              _vm._v(" "),
                              _c(
                                "Button",
                                {
                                  attrs: {
                                    type: "error",
                                    size: "small",
                                    loading: category.isDeleteting,
                                  },
                                  on: {
                                    click: function ($event) {
                                      return _vm.showDeletingModal(category, i)
                                    },
                                  },
                                },
                                [_vm._v("Delete")]
                              ),
                            ],
                            1
                          ),
                        ])
                      }),
                      _vm._v(" "),
                      _vm.categories.length <= 0
                        ? _c("tr", [_c("td", [_vm._v("No Data")])])
                        : _vm._e(),
                    ],
                    2
                  ),
                ]),
              ]),
            ]),
            _vm._v(" "),
            _c(
              "Modal",
              {
                attrs: {
                  title: "Add Category",
                  "mask-closable": false,
                  closable: false,
                },
                model: {
                  value: _vm.customFlags.AddModal,
                  callback: function ($$v) {
                    _vm.$set(_vm.customFlags, "AddModal", $$v)
                  },
                  expression: "customFlags.AddModal",
                },
              },
              [
                _c("Input", {
                  attrs: { placeholder: "Add category Name" },
                  model: {
                    value: _vm.categoryData.categoryName,
                    callback: function ($$v) {
                      _vm.$set(_vm.categoryData, "categoryName", $$v)
                    },
                    expression: "categoryData.categoryName",
                  },
                }),
                _vm._v(" "),
                _c("div", { staticClass: "space" }),
                _vm._v(" "),
                _c(
                  "Upload",
                  {
                    ref: "uploads",
                    attrs: {
                      type: "drag",
                      headers: {
                        "x-csrf-token": _vm.token,
                        "X-Requested-With": "XMLHttpRequest",
                      },
                      "on-success": _vm.handleSuccess,
                      format: ["jpg", "jpeg", "png"],
                      "max-size": 2048,
                      "on-error": _vm.handleError,
                      "on-exceeded-size": _vm.handleMaxSize,
                      "on-format-error": _vm.handleFormatError,
                      action: "/app/upload",
                    },
                  },
                  [
                    _c(
                      "div",
                      { staticStyle: { padding: "20px 0" } },
                      [
                        _c("Icon", {
                          staticStyle: { color: "#3399ff" },
                          attrs: { type: "ios-cloud-upload", size: "52" },
                        }),
                        _vm._v(" "),
                        _c("p", [_vm._v("Click or drag files here to upload")]),
                      ],
                      1
                    ),
                  ]
                ),
                _vm._v(" "),
                _vm.categoryData.iconImage
                  ? _c("div", { staticClass: "demo-upload-list" }, [
                      _c("img", {
                        attrs: {
                          src:
                            "/uploads/category/" + _vm.categoryData.iconImage,
                          alt: "",
                        },
                      }),
                      _vm._v(" "),
                      _c(
                        "div",
                        { staticClass: "demo-upload-list-cover" },
                        [
                          _c("Icon", { attrs: { type: "ios-eye-outline" } }),
                          _vm._v(" "),
                          _c("Icon", {
                            attrs: { type: "ios-trash-outline" },
                            on: { click: _vm.deleteImage },
                          }),
                        ],
                        1
                      ),
                    ])
                  : _vm._e(),
                _vm._v(" "),
                _c(
                  "div",
                  { attrs: { slot: "footer" }, slot: "footer" },
                  [
                    _c(
                      "Button",
                      {
                        attrs: { type: "default" },
                        on: {
                          click: function ($event) {
                            _vm.customFlags.AddModal = false
                          },
                        },
                      },
                      [_vm._v("Close")]
                    ),
                    _vm._v(" "),
                    _c(
                      "Button",
                      {
                        attrs: {
                          type: "primary",
                          disabled: _vm.isAdding,
                          loading: _vm.isAdding,
                        },
                        on: { click: _vm.saveData },
                      },
                      [_vm._v(_vm._s(_vm.isAdding ? "Saving..." : "Save"))]
                    ),
                  ],
                  1
                ),
              ],
              1
            ),
            _vm._v(" "),
            _c(
              "Modal",
              {
                attrs: {
                  title: "Edit Category",
                  "mask-closable": false,
                  closable: false,
                },
                model: {
                  value: _vm.customFlags.EditModal,
                  callback: function ($$v) {
                    _vm.$set(_vm.customFlags, "EditModal", $$v)
                  },
                  expression: "customFlags.EditModal",
                },
              },
              [
                _c("Input", {
                  attrs: { placeholder: "Edit category Name" },
                  model: {
                    value: _vm.editCategoryData.categoryName,
                    callback: function ($$v) {
                      _vm.$set(_vm.editCategoryData, "categoryName", $$v)
                    },
                    expression: "editCategoryData.categoryName",
                  },
                }),
                _vm._v(" "),
                _c("div", { staticClass: "space" }),
                _vm._v(" "),
                _c(
                  "Upload",
                  {
                    ref: "uploads",
                    attrs: {
                      type: "drag",
                      headers: {
                        "x-csrf-token": _vm.token,
                        "X-Requested-With": "XMLHttpRequest",
                      },
                      "on-success": _vm.handleSuccess,
                      format: ["jpg", "jpeg", "png"],
                      "max-size": 2048,
                      "on-error": _vm.handleError,
                      "on-exceeded-size": _vm.handleMaxSize,
                      "on-format-error": _vm.handleFormatError,
                      action: "/app/upload",
                    },
                  },
                  [
                    _c(
                      "div",
                      { staticStyle: { padding: "20px 0" } },
                      [
                        _c("Icon", {
                          staticStyle: { color: "#3399ff" },
                          attrs: { type: "ios-cloud-upload", size: "52" },
                        }),
                        _vm._v(" "),
                        _c("p", [_vm._v("Click or drag files here to upload")]),
                      ],
                      1
                    ),
                  ]
                ),
                _vm._v(" "),
                _vm.editCategoryData.iconImage
                  ? _c("div", { staticClass: "demo-upload-list" }, [
                      _c("img", {
                        attrs: {
                          src:
                            "/uploads/category/" +
                            _vm.editCategoryData.iconImage,
                          alt: "",
                        },
                      }),
                      _vm._v(" "),
                      _c(
                        "div",
                        { staticClass: "demo-upload-list-cover" },
                        [
                          _c("Icon", { attrs: { type: "ios-eye-outline" } }),
                          _vm._v(" "),
                          _c("Icon", {
                            attrs: { type: "ios-trash-outline" },
                            on: { click: _vm.deleteImage },
                          }),
                        ],
                        1
                      ),
                    ])
                  : _vm._e(),
                _vm._v(" "),
                _c(
                  "div",
                  { attrs: { slot: "footer" }, slot: "footer" },
                  [
                    _c(
                      "Button",
                      {
                        attrs: { type: "default" },
                        on: {
                          click: function ($event) {
                            _vm.customFlags.EditModal = false
                          },
                        },
                      },
                      [_vm._v("Close")]
                    ),
                    _vm._v(" "),
                    _c(
                      "Button",
                      {
                        attrs: {
                          type: "primary",
                          disabled: _vm.isAdding,
                          loading: _vm.isAdding,
                        },
                        on: { click: _vm.saveData },
                      },
                      [_vm._v(_vm._s(_vm.isAdding ? "Updating..." : "Update"))]
                    ),
                  ],
                  1
                ),
              ],
              1
            ),
            _vm._v(" "),
            _c(
              "Modal",
              {
                attrs: { width: "360" },
                model: {
                  value: _vm.showDeleteModal,
                  callback: function ($$v) {
                    _vm.showDeleteModal = $$v
                  },
                  expression: "showDeleteModal",
                },
              },
              [
                _c(
                  "p",
                  {
                    staticStyle: { color: "#f60", "text-align": "center" },
                    attrs: { slot: "header" },
                    slot: "header",
                  },
                  [
                    _c("Icon", { attrs: { type: "ios-information-circle" } }),
                    _vm._v(" "),
                    _c("span", [_vm._v("Delete confirmation")]),
                  ],
                  1
                ),
                _vm._v(" "),
                _c("div", { staticStyle: { "text-align": "center" } }, [
                  _c("p", [
                    _vm._v("Are you sure you want to delete this tag?."),
                  ]),
                ]),
                _vm._v(" "),
                _c(
                  "div",
                  { attrs: { slot: "footer" }, slot: "footer" },
                  [
                    _c(
                      "Button",
                      {
                        attrs: {
                          type: "error",
                          size: "large",
                          long: "",
                          loading: _vm.modalLoading,
                        },
                        on: { click: _vm.deleteTag },
                      },
                      [_vm._v("Delete")]
                    ),
                  ],
                  1
                ),
              ]
            ),
          ],
          1
        ),
      ]),
    ]),
  ])
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "page-header" }, [
      _c("div", { staticClass: "page-block" }, [
        _c("div", { staticClass: "row align-items-center" }, [
          _c("div", { staticClass: "col-md-12" }, [
            _c("div", { staticClass: "page-header-title" }, [
              _c("h5", { staticClass: "m-b-10" }, [_vm._v("Category")]),
            ]),
            _vm._v(" "),
            _c("ul", { staticClass: "breadcrumb" }, [
              _c("li", { staticClass: "breadcrumb-item" }, [
                _c("a", { attrs: { href: "index.html" } }, [
                  _c("i", { staticClass: "feather icon-home" }),
                ]),
              ]),
              _vm._v(" "),
              _c("li", { staticClass: "breadcrumb-item" }, [
                _c("a", { attrs: { href: "#!" } }, [_vm._v("Dashboard")]),
              ]),
              _vm._v(" "),
              _c("li", { staticClass: "breadcrumb-item" }, [
                _c("a", { attrs: { href: "#!" } }, [_vm._v("Category")]),
              ]),
            ]),
          ]),
        ]),
      ]),
    ])
  },
]
render._withStripped = true



/***/ }),

/***/ "./resources/js/admin/pages/category.vue":
/*!***********************************************!*\
  !*** ./resources/js/admin/pages/category.vue ***!
  \***********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _category_vue_vue_type_template_id_525752b2___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./category.vue?vue&type=template&id=525752b2& */ "./resources/js/admin/pages/category.vue?vue&type=template&id=525752b2&");
/* harmony import */ var _category_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./category.vue?vue&type=script&lang=js& */ "./resources/js/admin/pages/category.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _category_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./category.vue?vue&type=style&index=0&lang=css& */ "./resources/js/admin/pages/category.vue?vue&type=style&index=0&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _category_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _category_vue_vue_type_template_id_525752b2___WEBPACK_IMPORTED_MODULE_0__["render"],
  _category_vue_vue_type_template_id_525752b2___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/admin/pages/category.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/admin/pages/category.vue?vue&type=script&lang=js&":
/*!************************************************************************!*\
  !*** ./resources/js/admin/pages/category.vue?vue&type=script&lang=js& ***!
  \************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_category_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./category.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/admin/pages/category.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_category_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/admin/pages/category.vue?vue&type=style&index=0&lang=css&":
/*!********************************************************************************!*\
  !*** ./resources/js/admin/pages/category.vue?vue&type=style&index=0&lang=css& ***!
  \********************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_category_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader!../../../../node_modules/css-loader??ref--6-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--6-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./category.vue?vue&type=style&index=0&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/admin/pages/category.vue?vue&type=style&index=0&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_category_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_category_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_category_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_category_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/admin/pages/category.vue?vue&type=template&id=525752b2&":
/*!******************************************************************************!*\
  !*** ./resources/js/admin/pages/category.vue?vue&type=template&id=525752b2& ***!
  \******************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_category_vue_vue_type_template_id_525752b2___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./category.vue?vue&type=template&id=525752b2& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/admin/pages/category.vue?vue&type=template&id=525752b2&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_category_vue_vue_type_template_id_525752b2___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_category_vue_vue_type_template_id_525752b2___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);