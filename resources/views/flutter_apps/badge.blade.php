<!DOCTYPE html>
<html lang="en">
<title>It's All Widgets!</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="./favicon.png">
<link crossorigin="anonymous" href="https://fonts.googleapis.com/icon?family=Google+Sans:400&display=swap" rel="stylesheet" />
<style>
  * {
    box-sizing: border-box;
    --shadow-elevation-1: 0 2px 4px -1px rgba(0, 0, 0, .2), 0 4px 5px 0 rgba(0, 0, 0, .14), 0 1px 10px 0 rgba(0, 0, 0, .12);
    --shadow-elevation-2: 0 3px 5px -1px rgba(0, 0, 0, .2), 0 6px 10px 0 rgba(0, 0, 0, .14), 0 1px 18px 0 rgba(0, 0, 0, .12);
    --button-color: #2979ff;
    --button-color-hover: #3580fd;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
  }

  body {
    background: #e0e0e0;
    margin: 0;
    font-family: 'Google Sans', sans-serif;
    display: flex;
    flex-direction: column;
    color: #4a4a4a;
    min-height: 100vh;
  }

  header {
    background-color: #fff;
    height: 76px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    box-shadow: 0 2px 4px -1px rgba(0, 0, 0, .2), 0 4px 5px 0 rgba(0, 0, 0, .14), 0 1px 10px 0 rgba(0, 0, 0, .12);
    z-index: 1;
  }

  .main-container {
    display: flex;
    flex: 1;
  }

  .input-panel {
    padding: 24px;
    width: 40%;
    max-width: 420px;
    background-color: #f5f5f5;
    box-shadow: 0 1px 5px 0 rgba(0, 0, 0, .2), 0 2px 2px 0 rgba(0, 0, 0, .14), 0 3px 1px -2px rgba(0, 0, 0, .12);
  }

  .input {
    margin-bottom: 36px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
  }

  .input:last-child {
    margin-bottom: 0;
  }

  .input label {
    font-size: 13px;
    margin-bottom: 8px;
    font-weight: 500;
  }

  .button {
    color: #fff;
    font-size: 15px;
    border-radius: 4px;
    padding: 10px 12px;
    box-shadow: var(--shadow-elevation-1);
    background-color: var(--button-color);
    cursor: pointer;
    border: none;
    transition: box-shadow 100ms ease, background-color 100ms ease;
  }

  .button:hover {
    box-shadow: var(--shadow-elevation-2);
    background-color: var(--button-color-hover);
  }

  input.category-input {
    font-size: 15px;
    padding: 8px;
    background: #fff;
    border: 1px solid #cacaca;
    border-radius: 4px;
    width: 100%;
    max-width: 360px;
  }

  input.category-input:focus {
    outline: none;
    border-color: #8e8e8e;
  }

  .select-container {
    display: flex;
    border-radius: 4px;
    border: 1px solid #cacaca;
    overflow: hidden;
  }

  .select-container .select {
    background-color: #fff;
    padding: 6px 12px;
    font-size: 15px;
    cursor: pointer;
    transition: background-color 100ms ease;
  }

  .select-container .select:not(:last-child) {
    border-right: 1px solid #cacaca;
  }

  .select-container .select[selected] {
    color: #fff;
    background-color: var(--button-color);
  }

  .preview-panel {
    padding: 36px 0;
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
  }

  canvas {
    width: 500px;
    max-width: 80%;
  }

  .download-fab {
    cursor: pointer;
    position: absolute;
    bottom: 24px;
    right: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    width: 64px;
    height: 64px;
    background-color: #2979ff;
    box-shadow: var(--shadow-elevation-1);
    transition: background-color 100ms ease, box-shadow 100ms ease;
  }

  .download-fab:hover {
    background-color: #3580fd;
    box-shadow: var(--shadow-elevation-2);
  }

  @media screen and (max-width: 860px) {
    header {
      height: 56px;
      font-size: 20px;
    }
    .main-container {
      flex-direction: column;
    }
    .input-panel,
    .preview-panel {
      width: 100%;
      max-width: initial;
    }
    .preview-panel {
      padding: 24px 0 96px 0;
      flex: 1;
    }
    .input {
      margin-bottom: 24px;
    }
    .download-fab {
      width: 56px;
      height: 56px;
    }
  }
</style>

<header>
  <div class="title">30 Days of Flutter • Badge Generator</div>
</header>

<div class="main-container">
  <div class="input-panel">
    <div class="input">
      <label>Profile Picture</label>
      <button class="button" onclick="uploadImage()">Upload Image</button>
      <input class="profile-input" type="file" accept="image/*" onchange="upload(event)" hidden />
    </div>
    <div class="input" style="display:none">
      <label for="category">Category Name</label>
      <input id="category" list="gde-category" class="category-input" type="text" oninput="draw()" />
      <datalist id="gde-category">
        <option value="Android">
        <option value="Angular">
        <option value="Assistant">
        <option value="Dart">
        <option value="Firebase">
        <option value="Flutter">
        <option value="G Suite">
        <option value="Go">
        <option value="Google Cloud">
        <option value="Google Maps">
        <option value="IoT">
        <option value="Kotlin">
        <option value="Machine Learning">
        <option value="Payments">
        <option value="Web">
      </datalist>
    </div>
    <div class="input">
      <label>Image Shape</label>
      <div class="select-container">
        <!-- <div class="select" id="original" selected onclick="changeShape('original')">Original</div> -->
        <div class="select" id="square" selected onclick="changeShape('square')">Square</div>
        <div class="select" id="circle" onclick="changeShape('circle')">Circle</div>
      </div>
    </div>
  </div>
  <div class="preview-panel">
    <canvas></canvas>
    <div class="download-fab" onclick="download()">
      <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24" fill="#fff">
        <path d="M0 0h24v24H0V0z" fill="none" />
        <path
          d="M16.59 9H15V4c0-.55-.45-1-1-1h-4c-.55 0-1 .45-1 1v5H7.41c-.89 0-1.34 1.08-.71 1.71l4.59 4.59c.39.39 1.02.39 1.41 0l4.59-4.59c.63-.63.19-1.71-.7-1.71zM5 19c0 .55.45 1 1 1h12c.55 0 1-.45 1-1s-.45-1-1-1H6c-.55 0-1 .45-1 1z" />
      </svg>
    </div>
  </div>
</div>

<script>
  const canvas = document.querySelector('canvas')
  const ctx = canvas.getContext('2d')
  let image = ''
  let shape = 'square'

  const uploadImage = () => {
    document.querySelector('input.profile-input').click()
  }

  const changeShape = (type) => {
    //const original = document.querySelector('.select-container .select#original')
    const square = document.querySelector('.select-container .select#square')
    const circle = document.querySelector('.select-container .select#circle')
    shape = type
    switch (type) {
        /*
      case 'original': {
        original.setAttribute('selected', '')
        square.removeAttribute('selected')
        circle.removeAttribute('selected')
        break
      }
      */
      case 'square': {
        square.setAttribute('selected', '')
        //original.removeAttribute('selected')
        circle.removeAttribute('selected')
        break
      }
      case 'circle': {
        circle.setAttribute('selected', '')
        //original.removeAttribute('selected')
        square.removeAttribute('selected')
        break
      }
    }
    draw()
  }

  const upload = (e) => {
    if (e && e.target.files && e.target.files[0]) {
      const reader = new FileReader()
      reader.onload = (event) => {
        const img = new Image()
        img.onload = () => {
          image = img
          draw()
        }
        img.src = event.target.result
      }
      reader.readAsDataURL(e.target.files[0])
    }
  }

  const banner = new Image()
  banner.src = '{{ asset('images/badge.png') }}';
  banner.onload = () => {
    draw()
  }

  const draw = () => {
    //const category = document.querySelector('input.category-input').value || 'Category Name'
    const category = document.querySelector('input.category-input').value || ''

    if (image) {
      switch (shape) {
        case 'original': {
          canvas.width = image.width
          canvas.height = image.height
          ctx.drawImage(image, 0, 0)
          break
        }
        default: {
          const size = Math.min ( image.width, image.height )
          canvas.width = 500
          canvas.height = 500
          const hRatio = canvas.width / image.width
          const vRatio = canvas.height / image.height
          const ratio  = Math.max ( hRatio, vRatio )
          const x = ( canvas.width - image.width * ratio ) / 2
          const y = ( canvas.height - image.height * ratio ) / 2
          ctx.drawImage(image, 0, 0, image.width, image.height, x, y, image.width * ratio, image.height * ratio)
          break
        }
      }
    }
    else {
      ctx.canvas.width = 500
      ctx.canvas.height = 500
      ctx.fillStyle = '#0d47a1'
      ctx.fillRect(0, 0, canvas.width, canvas.height)
    }

    const height = banner.height / banner.width * canvas.width
    const y = canvas.height - height
    const fontSize = canvas.width / 17.2
    const fontY = y + height * 0.7
    ctx.drawImage(banner, 0, 0, banner.width, banner.height, 0, y, canvas.width, height)
    console.log(banner.width + ' ' + banner.height);

    ctx.fillStyle = '#757575'
    ctx.textAlign = 'center'
    ctx.textBaseline = 'middle'
    ctx.font = `${fontSize}px Google Sans, sans-serif`
    ctx.fillText(category, canvas.width / 2, fontY)

    if (shape === 'circle') {
      ctx.globalCompositeOperation='destination-in'
      ctx.beginPath()
      ctx.arc(canvas.width / 2, canvas.height / 2, canvas.height / 2, 0, Math.PI * 2)
      ctx.closePath()
      ctx.fill()
    }
  }

  const download = () => {
    const a = document.createElement('a')
    const url = canvas.toDataURL('image/png;base64')
    a.download = 'badge.png'
    a.href = url
    a.click()
  }
</script>

</html>
