import fetch from '@/assets/js/fetch'
import config from '@/assets/js/config'
import router from '../../router'

const host = config.host
// console.log(router)
const Pub = {
  host,
  go: (str, obj) => {
    router.push({path: str, query: obj})
  },
  back: function () {
    router.go(-1)
  },
  headers: {'authorization': localStorage.oauth ? `${JSON.parse(localStorage.oauth).token_type} ${JSON.parse(localStorage.oauth).access_token}` : ''},
  tsldev: config.dev,
  tsldev2: config.dev2,
  tsldev3: config.dev3,
  tsldev4: config.dev4,
  devname: config.devname2,
  noshow: false,
  devshow: config.devshow, // 只有测试环境显示的内容 
  repidf: function (mobile) {
    if (!(this.repid1.test(mobile) || this.repid2.test(mobile))) { // 身份证号判断
    }
  },
  get_export: function (path, str) {
    // console.log('daochu', location, `${location.origin}/api_admin${path}?${str}`)
    const link = document.createElement('a')
    link.style.display = 'none'
    link.target = '_blank'
    link.href = `${location.origin}/api_admin${path}?${str}`
    link.click()
  },
  downloadfile: function (path) {
    const link = document.createElement('a')
    link.style.display = 'none'
    link.target = '_blank'
    link.href = path
    link.click()
  },
  loginhost: config.loginhost,
  env: config.env,
  repid1: /^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{2}[0-9Xx]$/, // 最新身份证号
  repid2: /^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$/, // 最新身份证号
  reMobile: /^(13[0-9]|14[579]|15[0-3,5-9]|16[6]|17[0135678]|18[0-9]|19[89])\d{8}$/, // 最新手机号码验证11位数字
  rebankcardNo: /^([1-9]{1})(\d{14}|\d{18})$/, // 银行卡号校验
  validatePassPid: function (rule, value, callback) { // 身份证一般校验
    if (!/^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$/.test(value)) {
      callback(new Error('请填写正确身份证号！'))
    } else {
      callback()
    }
  },
  isCnNewID: function (cid) { // 身份证严格校验
    var arrExp = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2] // 加权因子
    var arrValid = [1, 0, "X", 9, 8, 7, 6, 5, 4, 3, 2] // 校验码
    if(/^\d{17}\d|x$/i.test(cid)){
      var sum = 0, idx;
      cid = cid.toString()
      for(var i = 0; i < cid.length - 1; i++){
        // 对前17位数字与权值乘积求和
        sum += parseInt(cid.substring(i, 1), 10) * arrExp[i]
      }
      // 计算模（固定算法）
      idx = sum % 11
      // 检验第18为是否与校验码相等
      return arrValid[idx] == cid.substring(17, 1).toUpperCase()
    }else{
      return false
    }
  },
  checkEmail: function (rule, value, callback) {
    if (!/^\w+@[a-zA-Z0-9]{2,10}(?:\.[a-z]{2,4}){1,3}$/.test(value)) {
      callback(new Error('请填写正确邮箱'))
    } else {
      callback()
    }
  },
  validatePass: function (rule, value, callback) {
    if (value) {
      if (!/^1[3456789]\d{9}$/.test(value)) {
        callback(new Error('请填写正确手机号'))
      } else {
        callback()
      }
    } else {
      callback()
    }
  },
  birthday: function (pid) { // 根据身份证号获取生日412702198710121452
    let str = pid.slice(6, 14)
    return `${str.slice(0, 4)}-${str.slice(5, 6)}-${str.slice(7, 8)}`
  },
  gender: function (value) { // 根据身份证号获取性别
    if (!/^1[3456789]\d{9}$/.test(value)) {
      callback(new Error('请填写正确手机号'))
    } else {
      callback()
    }
  },
  https: ({url, param}) => {
    return new Promise((resolve) => {
      Vue.axios({
        url: host + url,
        data: JSON.stringify(param),
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `${localStorage.token}`
        }
      }).then(res => {
        resolve(res.data)
      }, rej => {
        // console.log(rej)
      })
    })
  },
  time: (ms) => { // 根据毫秒获取日期
    let date = new Date(ms)
    let y = date.getFullYear()
    let m = (date.getMonth() + 1).toString()
    let d = date.getDate().toString()
    let h = date.getHours().toString()
    let f = date.getMinutes().toString()
    let s = date.getSeconds().toString()
    return `${y}-${m.length > 1 ? m : `0${m}`}-${d.length > 1 ? d : `0${d}`} ${h.length > 1 ? h : `0${h}`}:${f.length > 1 ? f : `0${f}`}:${s.length > 1 ? s : `0${s}`}`
  },
  get_days: (t1, t2) => { // 获取两个日期间的天数,时间格式2020-12-20 00:00:00，t1为小日期，t2为大日期
    t1 = t1.replace(/-/g, '/')
    t2 = t2.replace(/-/g, '/')
    // console.log(t1, t2)
    let d = parseInt((new Date(t2).getTime() - new Date(t1).getTime()) / 86400000)
    let h = parseInt((new Date(t2).getTime() - new Date(t1).getTime()) / 3600000 % 24)
    let m = parseInt((new Date(t2).getTime() - new Date(t1).getTime()) % 3600000 / 60000)
    let s = parseInt((new Date(t2).getTime() - new Date(t1).getTime()) % 3600000 % 60000 / 1000)
    // console.log(d, h, m, s)
    return { d, h, m, s }
  },
  get_zhou: (time) => { // 返回日期是周几
    const weekday = ['周日', '周一', '周二', '周三', '周四', '周五', '周六']
    return weekday[new Date(time).getDay()]
  },
  mday: (time) => { // 获取目标月天数时间格式2020-12-20
    // console.log(time)
    return new Date(time.slice(0, 4), time.slice(5, 7), 0).getDate()
  },
  days: (time1, time2) => { // 获取两个日期之间的间隔天数，时间格式2020-12-20
    let t1 = mday(time1) - time1.slice(8, 10)
    let t2 = Number(time2.slice(8, 10))
    let t3 = t1 + t2 + 1
    let m = 0
    if ((time1.slice(0, 4) - time2.slice(0, 4)) === 0) { // 年份相同
      m = time2.slice(5, 7) - time1.slice(5, 7) - 1
      for (let i = 1; i <= m; i++) {
        if (Number(time1.slice(5, 7)) + i > 9) {
          t3 = t3 + mday(`${time1.slice(0, 4)}-${Number(time1.slice(5, 7)) + i}-01`)
        } else {
          t3 = t3 + mday(`${time1.slice(0, 4)}-0${Number(time1.slice(5, 7)) + i}-01`)
        }
      }
      return t3
    } else if ((time1.slice(0, 4) - time2.slice(0, 4)) < 0) { // 年份不同time1小
      let k = 12 - time1.slice(5, 7)
      // console.log('k', k)
      for (let i = 1; i <= k; i++) {
        if (Number(time1.slice(5, 7)) + i > 9) {
          t3 = t3 + mday(`${time1.slice(0, 4)}-${Number(time1.slice(5, 7)) + i}-01`)
        } else {
          t3 = t3 + mday(`${time1.slice(0, 4)}-0${Number(time1.slice(5, 7)) + i}-01`)
        }
      }
      let n = time2.slice(5, 7) - 1
      // console.log('n', n)
      for (let i = 1; i <= n; i++) {
        if (Number(time2.slice(5, 7)) + i > 9) {
          t3 = t3 + mday(`${time2.slice(0, 4)}-${Number(time2.slice(5, 7)) - i}-01`)
        } else {
          t3 = t3 + mday(`${time2.slice(0, 4)}-0${Number(time2.slice(5, 7)) - i}-01`)
        }
      }
      if (time2.slice(0, 4) - time1.slice(0, 4) !== 1) { // 间隔一年以上
        let l = time2.slice(0, 4) - time1.slice(0, 4) - 1
        for (let i = 1; i <= l; i++) {
          for (let f = 1; f <= 12; f++) {
            if (f > 9) {
              t3 = t3 + mday(`${Number(time1.slice(0, 4)) + i}-${f}-01`)
            } else {
              t3 = t3 + mday(`${Number(time1.slice(0, 4)) + i}-0${f}-01`)
            }
          }
        }
      }
      return t3
    } else { // 年份不同time1大
      Message.warning({message: '小日期请放在前面'})
    }
  },
  info: function () {
    if (localStorage.info) {
      return JSON.parse(localStorage.info)
    }
  },
  getArrDifference: function (arr1, arr2) { // 找出两个数组的不同元素
    return arr1.concat(arr2).filter(function(v, i, arr) {
      return arr.indexOf(v) === arr.lastIndexOf(v)
    })
  },
  get_menu_btn: function (str) { // 获取菜单和权限按钮,str-系统菜单名
    let btnshow = {}
    let menulist = []
    return new Promise((resolve) => {
      if (localStorage.menu) {
        JSON.parse(localStorage.menu).forEach(item => {
          if (item.children) {
            item.children.forEach(child => {
              if (child.name === str) { // 筛选出要查询的菜单
                // console.log(child.name, str, child.other)
                if (child.other) {
                  child.other.forEach(item2 => {
                    if (item2.type === 3) {
                      menulist.push(item2)
                    }
                    btnshow[item2.path] = true
                  })
                }
              }
            })
          }
        })
        resolve({ btnshow, menulist })
      } else {
        fetch._getapi_s(`/menu`).then((res) => {
          if (res.code === 200) {
            res.data.forEach(item => {
              if (item.children) {
                item.children.forEach(child => {
                  if (child.name === str) { // 筛选出要查询的菜单
                    // console.log(child.name, str, child.other)
                    if (child.other) {
                      child.other.forEach(item2 => {
                        if (item2.type === 3) {
                          menulist.push(item2)
                        }
                        btnshow[item2.path] = true
                      })
                    }
                  }
                })
              }
            })
            resolve({ btnshow, menulist })
          }
        })
      }
      // console.log(btnshow)
    })
  },
  price: function (string) {
    let reg = /^[0-9][0-9.]{0,10}[0-9]{0}$/ // 带小数点价格金额判断
    if (!reg.test(string)) {
      Message.closeAll()
      Message.warning({offset: 100, message: '请输入正确的数字/小数最多两位!'})
      return ''
    }
    return Number(string)
  },
  num: (value) => { // 纯数字不带任何符号和字母
    // console.log(value)
    if (!((typeof value) === 'number' && Number(value))) {
      Message.closeAll()
      Message.warning({offset: 100, message: '请输入数字!'})
      return ''
    } else {
      return value
    }
  },
  xiaoshu: (value) => { // // 给金额加（.00）
    let balance = `${value}`
    let arr = `${balance}`.split('.')
    if (arr.length > 1) { // 已带小数点
      if (arr[1].length == 1) { // 一位小数点
        balance = `${balance}0`
      }
    } else { // 不带小数点
      balance = `${balance}.00`
    }
    balance = (Number(balance)).toFixed(2)
    // console.log(balance)
    return balance
  },
  objToStr: (obj) => { // 1.判断传的值是否为空，2.判断传的值是否必填，3.是否第一各参数
    let str = ``
    for (let [k, v] of Object.entries(obj)) {
      if (v || v === false) {
        str = `${str}&${k}=${v}`
      }
    }
    return str
  },
  pubtip: (obj) => {
    if (obj) {
      Notification({ title: obj.title ? obj.title : '操作', message: obj.message ? obj.message : '操作成功！', type: obj.type ? obj.type : 'info', duration: obj.duration ? obj.duration : 5000, dangerouslyUseHTMLString: obj.dangerouslyUseHTMLString ? true : false })
    } else {
      Notification({ title: '操作提示', message: '操作成功！', type: 'info', duration: 3000 })
    }
  },
  compare: (prop, type) => { // prop是要排序的属性,使用方法Array.sort(this.compare('prop'))
    // 默认传入两个参数，即为数组中要比较的两项
    return function (a, b) {
      var value1 = a[prop]
      var value2 = b[prop]
      console.log(value1, value2)
      // 通过返回值的正负来排序，返回值必须是数字类型
      if (type) {
        return value1 - value2
      } else {
        return value2 - value1
      }
    }
  },
  getStartEndTime: (type, bool) => { // 1-今日(默认)，2-昨日(最近一天)，7-最近7天，15-最近15天，30-最近30天，cm-本月，pm-上个月，m3-最近三个月，m6-最近半年，c7-本周，p7-上周
    if (bool) {
      console.log('getStartEndTime(type, bool),type值：1-今日(默认)，2-昨日(最近一天)，7-最近7天，15-最近15天，30-最近30天，cm-本月，pm-上个月，m3-最近三个月，m6-最近半年，c7-本周，p7-上周，bool为false或空不输出')
    }
    let date = new Date() // 当前时间
    let md1 = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31] // 平年
    let md2 = [31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31] // 闰年
    let mm = date.getMonth() + 1 // 当前月份0-11,需要加1
    let dd = date.getDate() // 当前日期 1~31
    let zz = date.getDay() // 礼拜几,0-6
    if (zz === 0) {
      zz = 7
    }
    // 开始年/月/日-截止年/月/日
    let y = date.getFullYear() // 年
    let m = mm // 月
    let d = dd // 日
    // 截止年/月/日
    let y2 = date.getFullYear()
    let m2 = mm
    let d2 = dd
    let ss = new Date(`${y}-${mm}-${dd} 23:59:59`).getTime() // 当前日期的最大毫秒
    if (y % 4 === 0) { // 闰年
      md1 = md2
    }
    if (type === 2) { // 昨天
      if (dd === 1 && mm === 1) { // 一月一日
        m= m2 = '12'
        d= d2 = '31'
        y = y2 = y - 1
      } else {
        if (dd - 1 === 0) { // 本月一号
          m = m2 = mm - 1
          d = d2 = md1[mm - 1 - 1]
        } else {
          d2 = d = dd - 1
        }
      }
    } else if (type === 'c7') { // 本周
      if (mm === 1 && dd - zz < 0) { // 跨年跨月一月一号
        y2 = y2 - 1
        m2 = '12'
        d2 = 31 + dd - zz + 1
        // console.log(`跨年跨月一月一号`)
      }
      if (dd - zz < 0){ // 天数减周数小于0说明，跨月
        m2 = mm - 1
        d2 = `${new Date(y, m2, 0).getDate() + dd - zz + 1}`
        // console.log(`天数减周数小于0说明，跨月`)
      } else {
        d2 = dd - zz + 1
      }
    } else if (type === 'p7') { // 上周
      // console.log(new Date(ss - 86400000 * (zz + 6) ), new Date(ss - 86400000))
      const now = new Date(); // 获取当前日期时间
      const currentDayOfWeek = now.getDay() // 当前日期是一周中的第几天（0-6，其中0代表周日，1代表周一，以此类推）

      // 计算上周一的日期
      let lastMonday = new Date(now.getTime())
      lastMonday.setDate(now.getDate() - currentDayOfWeek + 1) // 减去当前日期与周一之间的天数
      lastMonday.setDate(lastMonday.getDate() - 7) // 再减去7天，得到上周一的日期

      // 计算上周日的日期
      let lastSunday = new Date(lastMonday.getTime())
      lastSunday.setDate(lastMonday.getDate() + 6) // 上周一开始日期加6天，得到上周日的日期

      // 格式化日期为字符串（可根据需要调整格式）
      const startOfLastWeek = lastMonday.toISOString().split('T')[0] // 开始时间（上周一，ISO 8601 格式）
      const endOfLastWeek = lastSunday.toISOString().split('T')[0] // 结束时间（上周日，ISO 8601 格式）
      console.log('上周开始时间:', startOfLastWeek)
      console.log('上周结束时间:', endOfLastWeek)
      return [startOfLastWeek, endOfLastWeek]
    } else if (type === 'cm') { // 本月
      d2 = '01'
    } else if (type === 'pm') { // 上月
      if (mm === 1) { // 一月份
        m2 = m = '12'
        d = '31'
        y = y2 = y2 - 1
      } else {
        m = m2 = mm-1
        d = md1[mm-2]
      }
      d2 = '01'
    } else if (type === 'm3') { // 最近3个月
      if (mm - 3 < 0) { // 跨年
        y2 = y2 - 1
        m2 = mm - 2 + 12
        d2 = '01'
      } else {
        if (mm - 3 === 0) {
          m2 ='01'
        } else {
          m2 = mm - 3
        }
      }
    } else if (type === 'm6') { // 最近6个月
      if (mm - 6 < 0) {
        y2 = y2 - 1
        m2 = mm - 5 + 12
        d2 = '01'
      } else {
        m2 = mm - 6
      }
    } else { // 最近7天 最近15天 最近30天
      if (type > 1 && type < 31) {
        if (mm === 1 && dd - type < 0) { // 跨年跨月,一月
          y2 = y2 - 1
          m2 = '12'
          d2 = md1[mm - 1] + dd - type + 1
        } else {
          if (dd - type < 0) { // 跨月
            m2 = mm - 1
            d2 = md1[mm - 2] + dd - type + 1
            if (d2 < 1) { // 2月28-29天，不够30，跨两个月
              m2 = mm - 2
              d2 = md1[mm - 3] + md1[mm - 2] + dd - type + 1
            }
          } else {
            d2 = dd - type + 1
          }
        }
      }
    }
    m = `${m}`.length < 2 ? `0${m}` : m
    d = `${d}`.length < 2 ? `0${d}` : d
    m2 = `${m2}`.length < 2 ? `0${m2}` : m2
    d2 = `${d2}`.length < 2 ? `0${d2}` : d2
    // console.log([`${y2}-${m2}-${d2}`, `${y}-${m}-${d}`])
    return [`${y2}-${m2}-${d2}`, `${y}-${m}-${d}`]
  },
  addlog: (type, title, content, mark, storeid, modeid) => { // 添加修改日志,type类型，title标题，content新内容，mark原内容
    return new Promise((resolve) => {
      fetch._post(`common-api`, { type, title, content, mark, storeid, modeid }).then((res) => {
        if (res.code === 200) {
          resolve(res)
        }
      })
    })
  },
  zyq_list: (api, query = '') => {
    let str = '?'
      for (const [k, v] of Object.entries(query)) {
        if (v || v === 0) {
          str = `${str}${k}=${v}&`
        }
      }
    return new Promise((resolve) => {
      fetch._getapi_s(api + str.slice(0, -1), {}).then((res) => {
        if (res.code === 200) {
          resolve(res)
        } else {
          Notification({ title: '操作提示', message: res.message, type: 'info', duration: 3000 })
        }
      })
    })
  },
  zyq_add: (api, obj) => {
    return new Promise((resolve) => {
      fetch._postapi_s(api, obj).then((res) => {
        if (res.code === 200) {
        } else {
          Notification({ title: '操作提示', message: res.message, type: 'info', duration: 3000 })
        }
        resolve(res)
      })
    })
  },
  zyq_up: (api, obj, arr, bool) => {
    return new Promise((resolve) => {
      fetch._putapi_s(api, obj, arr).then((res) => {
        if (res.code === 200) {
          if (bool) {
            Notification({ title: '操作提示', message: res.message, type: 'success', duration: 1500 })
          }
          resolve(res)
        } else {
          Notification({ title: '操作提示', message: res.message, type: 'info', duration: 3000 })
        }
      })
    })
  },
  zyq_del: (api, bool) => {
    return new Promise((resolve) => {
      if (bool) {
        fetch._deleteapi_s(api, {}).then((res) => {
          if (res.code === 200) {
            resolve(res)
          } else {
            Notification({ title: '操作提示', message: res.msg, type: 'info', duration: 3000 })
          }
        })
      } else {
        MessageBox.confirm('此操作将永久删除该项, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          fetch._deleteapi_s(api, {}).then((res) => {
            if (res.code === 200) {
              resolve(res)
            } else {
              Notification({ title: '操作提示', message: res.message, type: 'info', duration: 3000 })
            }
          })
        })
      }
    })
  },
  zyq_detail: (api) => {
    return new Promise((resolve) => {
      fetch._getapi_s(api, {}).then((res) => {
        if (res.code === 200) {
          resolve(res)
        } else {
          Notification({ title: '操作提示', message: res.message, type: 'info', duration: 3000 })
        }
      })
    })
  }
}
export default Pub
