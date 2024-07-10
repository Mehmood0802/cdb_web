const dev = {
    ossconfig: { accessKeyId: '', accessKeySecret: '', bucket: '', region: '' },
    host: 'https://apis.mbuymart.com/api_admin/',
    wss: 'ws://106.14.173.97:8091/mdzx-api_admin/',
    loginhost: 'https://apis.mbuymart.com/',
    aliurl: '',
    ossurl: '/api_admin/uploads',
    producename: 'PowerOn v2.0',
    pathname: 'admindev',
    env: 'dev'
}
const pro = {
    ossconfig: { accessKeyId: '', accessKeySecret: '', bucket: '', region: '' },
    host: 'https://admin.poweronsg.com/api/',
    wss: 'wss:/apis.mbuymart.com/',
    loginhost: 'https://admin.poweronsg.com/',
    aliurl: '',
    ossurl: '/api_admin/uploads',
    producename: 'PowerOn v2.0',
    pathname: 'adminpro',
    env: 'pro'
}

export default dev // 开发环境
// export default pro // 生产环境
