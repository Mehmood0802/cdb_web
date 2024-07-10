const floatTool = {
  isInteger: function (obj) {
    return Math.floor(obj) === obj
  },
  toInteger: function (floatNum) {
    var ret = {times: 1, num: 0}
    if (this.isInteger(floatNum)) {
      ret.num = floatNum
      return ret
    }
    var strfi = floatNum + ''
    var dotPos = strfi.indexOf('.')
    var len = strfi.substr(dotPos + 1).length
    var times = Math.pow(10, len)
    var intNum = parseInt(floatNum * times + 0.5, 10)
    ret.times = times
    ret.num = intNum
    return ret
  },
  operation: function (a, b, op) {
    var o1 = this.toInteger(a)
    var o2 = this.toInteger(b)
    var n1 = o1.num
    var n2 = o2.num
    var t1 = o1.times
    var t2 = o2.times
    var max = t1 > t2 ? t1 : t2
    var result = null
    var that = this
    switch (op) {
      case 'add':
        if (t1 === t2) { // 两个小数位数相同
          result = n1 + n2
        } else if (t1 > t2) { // o1 小数位 大于 o2
          result = n1 + n2 * (t1 / t2)
        } else { // o1 小数位 小于 o2
          result = n1 * (t2 / t1) + n2
        }
        return result / max
      case 'subtract':
        if (t1 === t2) {
          result = n1 - n2
        } else if (t1 > t2) {
          result = n1 - n2 * (t1 / t2)
        } else {
          result = n1 * (t2 / t1) - n2
        }
        return result / max
      case 'multiply':
        result = (n1 * n2) / (t1 * t2)
        return result
      case 'divide':
        result = (function () {
          var r1 = n1 / n2
          var r2 = t2 / t1
          return that.operation(r1, r2, 'multiply') // 这里this会出问题所以要提前换成that 20190902
        }())
        return result
    }
  },
  // 加减乘除的四个接口
  add: function (a, b) {
    return this.operation(a, b, 'add') // +
  },
  subtract: function (a, b) {
    return this.operation(a, b, 'subtract')// -
  },
  multiply: function (a, b) {
    return this.operation(a, b, 'multiply')// *
  },
  divide: function (a, b) {
    return this.operation(a, b, 'divide')// /
  }
}

export default floatTool
