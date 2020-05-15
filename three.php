<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">
    <title>Pong Game</title>
    <link href="three.jpg" rel="icon">
</head>
<body>
    <style>
        
        #field {
            display: block;
            margin: 0 auto;
            border-top: 3px solid rgb(200, 0, 0);
            border-bottom: 3px solid rgb(200, 0, 0);
        }
        h1{
            text-align: center;
        }
    </style>
    <script>
        const fieldHeight = 400
const fieldWidth = 500

class Platform {
  constructor(x) {
    this.x = x
    this.y = (fieldHeight - Platform.height) / 2
  }

  draw(ctx) {
    ctx.fillStyle = Platform.color
    ctx.fillRect(
      this.x,
      this.y,
      Platform.width,
      Platform.height,
    )
  }
}

Platform.width = 10
Platform.height = 100
Platform.color = '#0f0f0f'
Platform.speed = 20

class Player extends Platform {
  constructor(x, keyUpCode, keyDownCode) {
    const y = (fieldHeight - Platform.height) / 2
    super(x, y)
    this.keyUpCode = keyUpCode
    this.keyDownCode = keyDownCode
  }

  movePlatformByEvent(e) {
    switch(e.keyCode) {
      case this.keyUpCode: {
        if (this.y > 0) {
          this.y -= Platform.speed
        }
        break
      }
      case this.keyDownCode: {
        if (this.y < fieldHeight - Platform.height) {
          this.y += Platform.speed
        }
        break
      }
    }
  }
}

class Boll {
  constructor() {
    this.setInitialProps()
  }

  setInitialProps(direction) {
    const directionKoef = (direction === 'right') ? 1 : -1
    this.x = fieldWidth / 2
    this.y = fieldHeight / 2
    this.angle = Math.random() * (Math.PI / 2) - Math.PI / 4
    this.speed = directionKoef * Math.abs(Boll.initialSpeed)
  }

  draw(ctx) {
    ctx.beginPath()
    ctx.arc(
      this.x,
      this.y,
      Boll.radius,
      0,
      2 * Math.PI,
      false
    )
    ctx.fillStyle = Boll.color
    ctx.fill()
  }
}

Boll.initialSpeed = 4
Boll.color = '#f70505'
Boll.radius = 5

const core = (pong) => {
  const {
    boll,
    player1,
    player2,
    score,
  } = pong

  if (
    (boll.y <= Boll.radius) ||
    (boll.y + Boll.radius >= fieldHeight)
  ) {
    boll.speed = -boll.speed
    boll.angle = Math.PI - boll.angle
    return
  }

  if (boll.x - Boll.radius <= Platform.width) {
    // collision with platform
    if (
      (boll.y + Boll.radius >= player1.y) &&
      (boll.y - Boll.radius <= player1.y + Platform.height) &&
      (boll.speed * Math.cos(boll.angle) < 0)
    ) {
      const shift = (player1.y + (Platform.height / 2) - boll.y) / (Platform.height / 2)
      const shiftCoef = (shift / 2) + 0.5

      boll.angle = -(shiftCoef * (Math.PI / 2) - Math.PI / 4)
      boll.speed = -boll.speed
      return
    }
  }

  if (boll.x + Boll.radius >= fieldWidth - Platform.width) {
    // collision with platform
    if (
      (boll.y - Boll.radius >= player2.y) &&
      (boll.y + Boll.radius <= player2.y + Platform.height) &&
      (boll.speed * Math.cos(boll.angle) > 0)
    ) {
      const shift = (player2.y + (Platform.height / 2) - boll.y) / (Platform.height / 2)
      const shiftCoef = (shift / 2) + 0.5

      boll.angle = (shiftCoef * (Math.PI / 2) - Math.PI / 4)
      boll.speed = -boll.speed
      return
    }
  }

  if (boll.x <= Boll.radius) {
    score.player2 += 1
    boll.setInitialProps('right')
    return
  }

  if (boll.x >= fieldWidth - Boll.radius) {
    score.player1 += 1
    boll.setInitialProps('left')
    return
  }
}

const renderScore = (ctx, {player1, player2}) => {
  ctx.fillStyle = 'red'
  ctx.textAlign = 'center'
  ctx.font = '35px Comic Sans MS'
  ctx.fillText(`${player1}:${player2}`, fieldWidth / 2, 50)
}

const requestAnimationFrame = window.requestAnimationFrame

const render = (ctx, pong) => {
  const {
    player1,
    player2,
    boll,
    score,
  } = pong

  core(pong)

  boll.y += Math.round(boll.speed * Math.sin(boll.angle))
  boll.x += Math.round(boll.speed * Math.cos(boll.angle))

  ctx.clearRect(0, 0, fieldWidth, fieldHeight)

  renderScore(ctx, score)

  player1.draw(ctx)
  player2.draw(ctx)
  boll.draw(ctx)

  requestAnimationFrame(() => render(ctx, pong))
}

window.onload = () => {
  const canvas = document.getElementById('field')
  const ctx = canvas.getContext('2d')

  const player1 = new Player(0, 87, 83)
  const player2 = new Player(fieldWidth - Platform.width, 38, 40)

  const pong = {
    player1,
    player2,
    boll: new Boll(),
    score: {
      player1: 0,
      player2: 0,
    },
  }

  addEventListener(
    'keydown',
    (e) => {
      player1.movePlatformByEvent.bind(player1)(e)
      player2.movePlatformByEvent.bind(player2)(e)
    }
  )

  render(ctx, pong)
}
    </script>
    <h1>PONG GAME</h1>
    <canvas id="field" width="500px" height="400px"></canvas>
</body>
</html>