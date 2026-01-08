<!DOCTYPE html>
<html lang="en">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <title>AthletiX - Control Panel</title>
    <link rel="stylesheet" href="globals.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      /** @type {import('tailwindcss').Config} */
      tailwind.config = {
        theme: {
          extend: {},
        },
        plugins: [],
      };
    </script>
  </head>
  <body>
    <main class="bg-white grid justify-items-center [align-items:start] w-screen">
      <div class="bg-white overflow-hidden border border-solid border-black w-[1280px] h-[720px] relative">
        <header class="absolute w-[296px] h-[97px] top-6 left-[145px]">
          <div class="absolute top-0 right-4">
            <a href="login.html" class="text-[#8c2c08] hover:underline text-sm">Logout</a>
          </div>
          <h1
            class="absolute w-[296px] top-0 left-0 [font-family:'Inknut_Antiqua',Helvetica] font-bold text-[#8c2c08] text-2xl tracking-[0] leading-[normal]"
          >
            Control Panel
          </h1>
          <div
            class="absolute top-[69px] left-[59px] [font-family:'Arial-Bold',Helvetica] font-bold text-black text-2xl tracking-[0] leading-[normal] whitespace-nowrap"
            role="text"
            aria-label="Current user role"
          >
            Admin
          </div>
        </header>
        <nav class="absolute w-[117px] h-[720px] top-0 left-0" role="navigation" aria-label="Main navigation">
          <div class="absolute w-[113px] h-[720px] top-0 left-0 bg-[#8c2c08] border border-solid"></div>
          <img
            class="absolute w-[73px] h-[72px] top-2 left-5"
            src="https://c.animaapp.com/meod3nrskPlg16/img/logo.png"
            alt="Organization logo"
          />
          <img
            class="absolute w-[73px] h-[587px] top-[91px] left-5"
            src="https://c.animaapp.com/meod3nrskPlg16/img/sidebar-frame.svg"
            alt="Navigation menu icons"
            role="img"
          />
          <img
            class="absolute w-3 h-[57px] top-[194px] left-[105px]"
            src="https://c.animaapp.com/meod3nrskPlg16/img/line-3.png"
            alt="Navigation indicator"
            role="img"
          />
        </nav>
        <section class="dashboard-grid" aria-label="Admin dashboard functions">
          <article
            class="absolute w-[257px] h-[177px] top-[124px] left-[185px] bg-[#f7f0f0] rounded-[10px] border border-solid border-black"
            role="button"
            tabindex="0"
            aria-label="Dashboard - View analytics and statistics"
            onclick="window.location.href='dashboard.html'"
          >
            <h3
              class="absolute top-[85px] left-[85px] [font-family:'Arial-Bold',Helvetica] font-bold text-black text-xl tracking-[0] leading-[normal] whitespace-nowrap"
            >
              Dashboard
            </h3>
            <img
              class="absolute w-[61px] h-[45px] top-[25px] left-[89px]"
              src="https://c.animaapp.com/meod3nrskPlg16/img/vector-8.svg"
              alt="Dashboard icon"
              role="img"
            />
            <p
              class="absolute w-[179px] top-[115px] left-[38px] [font-family:'Arial-Regular',Helvetica] font-normal text-[#a4a2a2] text-[15px] text-center tracking-[0] leading-[normal]"
            >
              View analytics and performance statistics.
            </p>
          </article>
          <article
            class="absolute w-[257px] h-[177px] top-[345px] left-[185px] bg-[#f7f0f0] rounded-[10px] border border-solid border-black"
            role="button"
            tabindex="0"
            aria-label="Schedule Events - Plan practices, games and other team events"
          >
            <h3
              class="absolute top-[81px] left-[47px] [font-family:'Arial-Bold',Helvetica] font-bold text-black text-xl tracking-[0] leading-[normal] whitespace-nowrap"
            >
              Schedule Events
            </h3>
            <p
              class="absolute w-[179px] top-[111px] left-[38px] [font-family:'Arial-Regular',Helvetica] font-normal text-[#a4a2a2] text-[15px] text-center tracking-[0] leading-[normal]"
            >
              Plan practices, games and other team events.
            </p>
            <img
              class="absolute w-[50px] h-14 top-[13px] left-[99px]"
              src="https://c.animaapp.com/meod3nrskPlg16/img/vector-1.svg"
              alt="Calendar icon"
              role="img"
            />
          </article>
          <article
            class="absolute w-[257px] h-[177px] top-[578px] left-[185px] bg-[#f7f0f0] rounded-[10px] border border-solid border-black"
            role="button"
            tabindex="0"
            aria-label="Submit Attendance - Mark who attended practices and games"
          >
            <p
              class="absolute w-[179px] top-[117px] left-9 [font-family:'Arial-Regular',Helvetica] font-normal text-[#a4a2a2] text-[15px] text-center tracking-[0] leading-[normal]"
            >
              Mark who attended practices and games.
            </p>
            <h3
              class="absolute top-[91px] left-[34px] [font-family:'Arial-Bold',Helvetica] font-bold text-black text-xl tracking-[0] leading-[normal] whitespace-nowrap"
            >
              Submit Attendance
            </h3>
            <img
              class="absolute w-[50px] h-14 top-[25px] left-[94px]"
              src="https://c.animaapp.com/meod3nrskPlg16/img/vector-4.svg"
              alt="Attendance tracking icon"
              role="img"
            />
          </article>
          <article
            class="absolute w-[257px] h-[177px] top-[578px] left-[533px] bg-[#f7f0f0] rounded-[10px] border border-solid border-black"
            role="button"
            tabindex="0"
            aria-label="View Weekly Game Schedule - Check upcoming matches or practice sessions"
          >
            <div class="absolute w-[179px] h-[84px] top-[76px] left-[47px]">
              <p
                class="absolute w-[179px] top-[45px] left-0 [font-family:'Arial-Regular',Helvetica] font-normal text-[#a4a2a2] text-[15px] text-center tracking-[0] leading-[normal]"
              >
                Check upcoming matches or practice sessions.
              </p>
              <h3
                class="absolute top-0 left-3.5 [font-family:'Arial-Bold',Helvetica] font-bold text-black text-xl text-center tracking-[0] leading-[normal]"
              >
                View Weekly <br />Game Schedule
              </h3>
            </div>
            <img
              class="absolute w-[50px] h-14 top-[15px] left-[110px]"
              src="https://c.animaapp.com/meod3nrskPlg16/img/vector-1.svg"
              alt="Schedule viewing icon"
              role="img"
            />
          </article>
          <article
            class="absolute w-[257px] h-[177px] top-[575px] left-[896px] bg-[#f7f0f0] rounded-[10px] border border-solid border-black"
            role="button"
            tabindex="0"
            aria-label="Send Messages to Coach or Team - Use chat or bulletin board features"
          >
            <p
              class="absolute w-[179px] top-[120px] left-[42px] [font-family:'Arial-Regular',Helvetica] font-normal text-[#a4a2a2] text-[15px] text-center tracking-[0] leading-[normal]"
            >
              Use chat or bulletin board feautures.
            </p>
            <h3
              class="absolute top-[71px] left-[47px] [font-family:'Arial-Bold',Helvetica] font-bold text-black text-xl text-center tracking-[0] leading-[normal]"
            >
              Send Messages <br />to Coach or Team
            </h3>
            <img
              class="absolute w-[61px] h-[45px] top-[21px] left-[102px]"
              src="https://c.animaapp.com/meod3nrskPlg16/img/vector-6.svg"
              alt="Messaging icon"
              role="img"
            />
          </article>
          <article
            class="absolute w-[257px] h-[177px] top-[345px] left-[533px] bg-[#f7f0f0] rounded-[10px] border border-solid border-black"
            role="button"
            tabindex="0"
            aria-label="Post Announcements - Create and manage announcements"
            onclick="window.location.href='announcements.html'"
          >
            <h3
              class="absolute top-20 left-[35px] [font-family:'Arial-Bold',Helvetica] font-bold text-black text-xl tracking-[0] leading-[normal] whitespace-nowrap"
            >
              Post Announcements
            </h3>
            <p
              class="absolute w-[179px] top-[114px] left-[38px] [font-family:'Arial-Regular',Helvetica] font-normal text-[#a4a2a2] text-[15px] text-center tracking-[0] leading-[normal]"
            >
              Create and manage event announcements.
            </p>
            <img
              class="absolute w-[50px] h-14 top-3.5 left-[102px]"
              src="https://c.animaapp.com/meod3nrskPlg16/img/vector-7.svg"
              alt="Drill design icon"
              role="img"
            />
          </article>
          <article
            class="absolute w-[257px] h-[177px] top-[345px] left-[896px] bg-[#f7f0f0] rounded-[10px] border border-solid border-black"
            role="button"
            tabindex="0"
            aria-label="Analyze Performance - Review statistics and substitute roles"
          >
            <p
              class="absolute w-[179px] top-[108px] left-9 [font-family:'Arial-Regular',Helvetica] font-normal text-[#a4a2a2] text-[15px] text-center tracking-[0] leading-[normal]"
            >
              Review statistics and substitute roles.
            </p>
            <h3
              class="absolute top-[81px] left-[25px] [font-family:'Arial-Bold',Helvetica] font-bold text-black text-xl text-center tracking-[0] leading-[normal] whitespace-nowrap"
            >
              Analyze Performance
            </h3>
            <img
              class="absolute w-[50px] h-[50px] top-[18px] left-[110px]"
              src="https://c.animaapp.com/meod3nrskPlg16/img/vector.svg"
              alt="Performance analysis icon"
              role="img"
            />
          </article>
          <article
            class="absolute w-[257px] h-[177px] top-[124px] left-[533px] bg-[#f7f0f0] rounded-[10px] border border-solid border-black"
            role="button"
            tabindex="0"
            aria-label="View and Export Report - Export performance or attendance reports"
          >
            <p
              class="absolute w-[179px] top-[116px] left-[38px] [font-family:'Arial-Regular',Helvetica] font-normal text-[#a4a2a2] text-[15px] text-center tracking-[0] leading-[normal]"
            >
              Export performance or attendance reports.
            </p>
            <h3
              class="absolute top-[85px] left-3.5 [font-family:'Arial-Bold',Helvetica] font-bold text-black text-xl tracking-[0] leading-[normal] whitespace-nowrap"
            >
              View and Export Report
            </h3>
            <img
              class="absolute w-[45px] h-14 top-6 left-[105px]"
              src="https://c.animaapp.com/meod3nrskPlg16/img/vector-2.svg"
              alt="Report export icon"
              role="img"
            />
          </article>
          <article
            class="absolute w-[257px] h-[177px] top-[126px] left-[896px] bg-[#f7f0f0] rounded-[10px] border border-solid border-black"
            role="button"
            tabindex="0"
            aria-label="Manage Schedule and Facilities - Allocate training slots or meeting rooms"
          >
            <p
              class="absolute w-[179px] top-32 left-[42px] [font-family:'Arial-Regular',Helvetica] font-normal text-[#a4a2a2] text-[15px] text-center tracking-[0] leading-[normal]"
            >
              Allocate&nbsp;&nbsp;training slots or meeting rooms.
            </p>
            <h3
              class="absolute top-[82px] left-10 [font-family:'Arial-Bold',Helvetica] font-bold text-black text-xl text-center tracking-[0] leading-[normal]"
            >
              Manage Schedule <br />and Facilities
            </h3>
            <img
              class="absolute w-[50px] h-14 top-[19px] left-[102px]"
              src="https://c.animaapp.com/meod3nrskPlg16/img/vector-1.svg"
              alt="Facility management icon"
              role="img"
            />
          </article>
        </section>
        <div
          class="absolute top-[313px] left-[204px] [font-family:'Arial-Bold',Helvetica] font-bold text-black text-2xl tracking-[0] leading-[normal] whitespace-nowrap"
          role="heading"
          aria-level="2"
        >
          Coach
        </div>
        <div
          class="absolute top-[548px] left-[204px] [font-family:'Arial-Bold',Helvetica] font-bold text-black text-2xl tracking-[0] leading-[normal] whitespace-nowrap"
          role="heading"
          aria-level="2"
        >
          Team Captain
        </div>
      </div>
    </main>
  </body>
</html>
