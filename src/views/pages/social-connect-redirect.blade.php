<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Social Connect Redirect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body>
    <div class="min-h-screen flex items-center justify-center p-4" x-data="socialConnects">
        <div class="shadow-[rgba(0,_0,_0,_0.25)_0px_25px_50px_-12px] rounded-lg py-6 px-10 w-full lg:w-2/5">
            <div x-cloak x-show="loading">
                <h3 class="text-2xl text-center">Oauth user information fetch...</h3>
            </div>

            <div x-cloack x-show="!loading">
                <div class="text-center mb-10">
                    <h6 class="text-3xl mb-6" :class="completed ? 'text-green-500' : 'text-red-500'" x-text="title">
                    </h6>
                    <div>
                        <template x-if="completed">
                            <iconify-icon icon="teenyicons:tick-circle-outline"
                                class="text-9xl text-green-500"></iconify-icon>
                        </template>
                        <template x-if="!completed">
                            <iconify-icon icon="material-symbols:cancel-outline"
                                class="text-9xl text-red-500"></iconify-icon>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('socialConnects', () => ({
                gateway: null,
                code: null,
                title: '',
                loading: true,
                completed: false,

                async init() {
                    let url = new URLSearchParams(window.location.search)
                    this.gateway = url.get('gateway')
                    this.code = url.get('code')

                    await fetch(
                            `${window.origin}/api/v1/social-connects/auth-user`, {
                                method: 'POST',
                                headers: {
                                    Accept: 'application/json',
                                    'Content-Type': 'application/json'
                                }
                            })
                        .then(res => res.json())
                        .then(async (res) => {
                            this.loading = false;

                            if (res.status !== 'success') {
                                this.title = res.message;
                                this.completed = false;
                                return;
                            }

                            this.title = 'User information fetch successfully';
                            this.completed = true;

                            let ipnCall = await fetch(
                                `${window.origin}/api/v1/assign-plan`, {
                                    method: "POST",
                                    headers: {
                                        Accept: 'application/json',
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        transaction_id: this.transactionId
                                    })
                                });
                        })
                        .catch(err => console.log(err))
                }
            }))
        })
    </script>
</body>

</html>
