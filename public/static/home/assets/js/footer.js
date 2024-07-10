function openAppStore() {
  console.log('点击了openAppStore')
  var platform = navigator.userAgent
  var isiOS = /iPhone|iPad|iPod/.test(platform)

  if (isiOS) {
      var itunesLink = 'https://apps.apple.com/cn/app/poweron-sg/id6443550607'
      window.location.href = itunesLink;
  } else if (/(Android)/i.test(navigator.userAgent)) {
    var itunesLink = 'https://play.google.com/store/apps/details?id=com.poweronsg.charge'
    window.location.href = itunesLink;
  } else {
    console.log('不是移动设备')
  }
}