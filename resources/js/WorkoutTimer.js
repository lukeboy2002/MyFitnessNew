export function workoutTimer(startedAt, completedAt = null) {
    return {
        elapsedSeconds: 0,
        formattedTime: '00:00',
        interval: null,

        start() {
            this.update();

            if (completedAt) {
                return;
            }

            this.interval = setInterval(() => {
                this.update();
            }, 1000);
        },

        update() {
            const start = new Date(startedAt).getTime();
            const end = completedAt
                ? new Date(completedAt).getTime()
                : Date.now();

            this.elapsedSeconds = Math.max(
                0,
                Math.floor((end - start) / 1000)
            );

            this.formattedTime = this.format(
                this.elapsedSeconds
            );
        },

        format(seconds) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;

            if (hours > 0) {
                return [
                    hours,
                    minutes,
                    secs
                ]
                    .map(value => String(value).padStart(2, '0'))
                    .join(':');
            }

            return [
                minutes,
                secs
            ]
                .map(value => String(value).padStart(2, '0'))
                .join(':');
        }
    };
}

export function restTimer(defaultSeconds = 60) {
    const initialSecs = Number(defaultSeconds) > 0 ? Number(defaultSeconds) : 60;

    return {
        totalSeconds: initialSecs,
        remainingSeconds: initialSecs,
        isRunning: false,
        interval: null,
        isFinished: false,
        isVisible: false,
        hideTimeout: null,

        init() {
            window.addEventListener('start-rest-timer', (event) => {
                const secs = event.detail?.seconds ?? (typeof event.detail === 'number' ? event.detail : null);
                this.start(secs);
            });
        },

        start(seconds = null) {
            this.clearHideTimeout();
            this.isVisible = true;

            if (seconds !== null && seconds !== undefined && Number(seconds) > 0) {
                this.totalSeconds = Number(seconds);
                this.remainingSeconds = this.totalSeconds;
            } else if (this.remainingSeconds <= 0) {
                this.remainingSeconds = this.totalSeconds;
            }

            this.isFinished = false;
            this.isRunning = true;
            this.clearInterval();

            this.interval = setInterval(() => {
                if (this.remainingSeconds > 0) {
                    this.remainingSeconds--;
                    if (this.remainingSeconds === 0) {
                        this.finish();
                    }
                }
            }, 1000);
        },

        pause() {
            this.isRunning = false;
            this.clearInterval();
        },

        resume() {
            this.clearHideTimeout();
            this.isVisible = true;

            if (this.remainingSeconds <= 0) {
                this.remainingSeconds = this.totalSeconds;
                this.isFinished = false;
            }
            this.isRunning = true;
            this.clearInterval();

            this.interval = setInterval(() => {
                if (this.remainingSeconds > 0) {
                    this.remainingSeconds--;
                    if (this.remainingSeconds === 0) {
                        this.finish();
                    }
                }
            }, 1000);
        },

        toggle() {
            if (this.isRunning) {
                this.pause();
            } else {
                this.resume();
            }
        },

        addSeconds(seconds) {
            this.clearHideTimeout();
            this.isVisible = true;

            this.remainingSeconds = Math.max(0, this.remainingSeconds + seconds);
            if (this.remainingSeconds > this.totalSeconds) {
                this.totalSeconds = this.remainingSeconds;
            }
            if (this.remainingSeconds > 0) {
                this.isFinished = false;
            }
        },

        reset(seconds = null) {
            this.clearHideTimeout();
            this.isVisible = true;
            this.pause();
            this.isFinished = false;
            if (seconds !== null && Number(seconds) > 0) {
                this.totalSeconds = Number(seconds);
            }
            this.remainingSeconds = this.totalSeconds;
        },

        finish() {
            this.pause();
            this.isFinished = true;
            this.remainingSeconds = 0;
            this.playNotificationSound();
            if (typeof navigator !== 'undefined' && navigator.vibrate) {
                try {
                    navigator.vibrate([200, 100, 200]);
                } catch (e) {
                }
            }
            this.scheduleHide(5000);
        },

        scheduleHide(delayMs = 5000) {
            this.clearHideTimeout();
            this.hideTimeout = setTimeout(() => {
                this.isVisible = false;
            }, delayMs);
        },

        clearHideTimeout() {
            if (this.hideTimeout) {
                clearTimeout(this.hideTimeout);
                this.hideTimeout = null;
            }
        },

        clearInterval() {
            if (this.interval) {
                clearInterval(this.interval);
                this.interval = null;
            }
        },

        playNotificationSound() {
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) {
                    return;
                }
                const ctx = new AudioCtx();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(880, ctx.currentTime);
                gain.gain.setValueAtTime(0.15, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.6);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.6);
            } catch (e) {
            }
        },

        get formattedRemaining() {
            const minutes = Math.floor(this.remainingSeconds / 60);
            const secs = this.remainingSeconds % 60;
            return [minutes, secs].map(v => String(v).padStart(2, '0')).join(':');
        },

        get progressPercent() {
            if (!this.totalSeconds || this.totalSeconds <= 0) {
                return 0;
            }
            return Math.min(100, Math.max(0, ((this.totalSeconds - this.remainingSeconds) / this.totalSeconds) * 100));
        }
    };
}

if (typeof window !== 'undefined') {
    window.workoutTimer = workoutTimer;
    window.restTimer = restTimer;
}

