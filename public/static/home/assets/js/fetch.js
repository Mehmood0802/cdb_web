import { createApp } from 'vue'
import qs from 'qs'

const app = createApp()
const host4 = '/api_admin'

const httpapi_s = ({url, param, headers, ...other} = {}) => {
  // console.log('请求时返回', qs)
  return new Promise((resolve) => {
    app.axios({
      url: host4 + url,
      data: param,
      transformRequest: [function (data, headers) {
        return data;
      }],
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
        'Authorization': localStorage.token ? localStorage.token : '',
        'Token': localStorage.token ? localStorage.token : '',
        'Lang': localStorage.Lang ? localStorage.Lang : '',
        'Deviceid': localStorage.Deviceid ? localStorage.Deviceid : '',
        'Time': new Date().getTime(),
        ...headers
      },
      ...other
    }).then(res => {
      if (res.data.code === 200) {
        // console.log(url, res)
        resolve(res.data)
      } else {
        resolve(res.data)
        Message.error({message: res.data.message, offset: 150})
      }
    }, err => {
      Message.info({message: `error: ${err}`})
    })
  })
}
const _postapi_s = (url, param = {}, other) => {
  // console.log(param)
  const formData = new FormData()
  for (const [k, v] of Object.entries(param)) {
    formData.set(k, v)
  }
  return httpapi_s({
    url,
    param: formData,
    method: 'post',
    headers: {
    },
    ...other
  })
}
const _putapi_s = (url, param = {}, arr) => { // gps
  if (arr && arr.length > 0) {
    arr.forEach(item => {
      delete param[item]
    })
  }
  // console.log(param)
  return httpapi_s({
    url,
    param: qs.stringify(param),
    method: 'put',
    headers: {
      // 'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
    }
  })
}
const _getapi_s = (url, param = {}, other) => { // gps
  // console.log(param)
  const formData = new FormData()
  for (const [k, v] of Object.entries(param)) {
    formData.set(k, v)
  }
  return httpapi_s({
    url,
    param: formData,
    method: 'get',
    headers: {},
    ...other
  })
}
const _deleteapi_s = (url, param = {}, other) => { // gps
  // console.log(param)
  const formData = new FormData()
  for (const [k, v] of Object.entries(param)) {
    formData.set(k, v)
  }
  return httpapi_s({
    url,
    param: formData,
    method: 'delete',
    headers: {},
    ...other
  })
}
export default {
  _postapi_s,
  _getapi_s,
  _putapi_s,
  _deleteapi_s
}
