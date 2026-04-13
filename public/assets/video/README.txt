HERO VIDEO BACKGROUND
=====================

To enable the full-screen hero video, drop an MP4 file named exactly:

    hero.mp4

…into this folder. index.php detects it automatically and will render
a fullbleed <video> tag that auto-plays, loops, and is muted (required
for autoplay on mobile).

Recommendations:
  * 1920x1080 or 2560x1440 @ 24/30 fps
  * Short loop (12-25 seconds)
  * H.264 codec, ~3-6 Mbps bitrate, under 8 MB for fast load
  * Muted at export (there is no audio track on the <video>)
  * Luxury property b-roll, drone / aerial / interior walkthrough

Free sources:
  * pexels.com/videos/
  * pixabay.com/videos/
  * coverr.co

If no hero.mp4 is present the hero automatically falls back to the
rotating Ken-Burns image slideshow using the project cover images.
