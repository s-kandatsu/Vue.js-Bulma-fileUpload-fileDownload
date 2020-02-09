<template>
    <div class="section">
        <div class="container">
            <div class="field">
                <button class="button is-info" @click="reload()">
                    <span class="icon">
                        <i class="fas fa-sync-alt"></i>
                    </span>
                    <span>更新</span>
                </button>
            </div>
            <div class="box">
                <h2 class="title is-5">単体ファイルのアップロード・ダウンロード</h2>
                <div class="field">
                    <figure class="image is-128x128">
                        <img :src="fileLink">
                    </figure>
                </div>
                <div class="field">
                    <label class="label is-small">アップロードファイル</label>
                    <div class="file has-name is-fullwidth">
                        <label class="file-label">
                            <input class="file-input" type="file" name="resume" @change="onSingleFileChange">
                            <span class="file-cta">
                            <span class="file-icon">
                                <i class="fas fa-upload"></i>
                            </span>
                            <span class="file-label">
                                選択してください…
                            </span>
                            </span>
                            <span class="file-name">
                                {{ fileName }}
                            </span>
                        </label>
                    </div>
                </div>
                <div class="field">
                    <label class="label is-small">ダウンロードファイルのID</label>
                    <input class="input" v-model="downloadFileId">
                </div>
                <div class="field">
                    <button type="button" class="button is-success" @click="singleUpload()">登録</button>
                    <button type="button" class="button is-info" @click="singleDownload()">取得</button>
                    <button type="button" class="button" @click="singleClear()">クリア</button>
                </div>
            </div>
            <div class="box">
                <h2 class="title is-5">複数ファイルのアップロード</h2>
                <div class="field">
                    <label class="label is-small">アップロードファイル</label>
                    <div class="file">
                        <label class="file-label">
                            <input class="file-input" type="file" name="resume" @change="onMultipleFileChange">
                            <span class="file-cta">
                            <span class="file-icon">
                                <i class="fas fa-upload"></i>
                            </span>
                            <span class="file-label">
                                選択してください…
                            </span>
                            </span>
                        </label>
                    </div>
                </div>
                <div class="field">
                    <span class="tags">
                        <span class="tag is-info"
                            v-for="(file, index) in fileUploadList"
                            :key="file.id">
                            {{ file.name }}
                            <a class="delete" @click="deleteUploadMultiple(index)"></a>
                        </span>
                    </span>
                </div>
                <div class="field">
                    <button type="button" class="button is-success" @click="multipleUpload()">登録</button>
                    <button type="button" class="button" @click="multipleClear()">クリア</button>
                </div>
            </div>
            <div class="box">
                <h2 class="title is-5">ファイル一覧</h2>
                <div class="field" v-if="fileViewList.length > 0">
                    <button type="button" class="button is-danger" @click="multipleDelete()">
                        <span class="icon">
                            <i class="fas fa-times-circle"></i>
                        </span>
                        <span>全削除</span>
                    </button>
                </div>
                <div class="field">
                    <span class="tags">
                        <span class="tag is-info"
                            v-for="file in fileViewList"
                            :key="file.id">
                            <a class="has-text-white"
                            :href="'/api/download?id=' + file.id"
                            :download="file.name">
                                {{ file.name }}
                            </a>
                            <span class="delete" @click="singleDelete(file)"></span>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Index",
    data () {
        return {
            fileValue: null,
            fileName: '',
            fileLink: '',
            downloadFileId: '',
            fileViewList: [],
            fileUploadList: [],
        }
    },
    methods: {
        // 添付ファイル読み込み処理（単体）
        onSingleFileChange: function (e) {
            const me = this;

            const files = e.target.files || e.dataTransfer.files;
            this.fileValue = files[0];
            this.fileName = files[0].name;

            const reader = new FileReader();
            reader.readAsDataURL(files[0]);

            reader.onload = function(){
                const url = reader.result;

                me.fileLink = url;
            }
        },
        // 添付ファイル読み込み処理（単体）
        onMultipleFileChange: function (e) {
            const me = this;

            const files = e.target.files || e.dataTransfer.files;
            const value = files[0];
            const name = files[0].name;

            const reader = new FileReader();
            reader.readAsDataURL(files[0]);

            reader.onload = function(){
                const url = reader.result;

                const path = url;

                me.fileUploadList.push({'value': value, 'name': name, 'path': path});
            }
        },
        // アップロード（単体）
        singleUpload: async function () {
            const me = this;

            // 選択チェック
            if (! this.fileValue) {
                this.$buefy.toast.open({
                    message: 'ファイルを選択してください',
                    type: 'is-danger'
                })
                return;
            }

            // リクエストデータ
            const formData = new FormData()
            formData.append('file', this.fileValue, this.fileName)

            await axios.post('/api/upload', formData)
            .then( async function (response) {
                // 取得成功
                me.singleClear(false);
                me.getList();

                me.$buefy.toast.open({
                    message: '保存しました',
                    type: 'is-success'
                })
            })
            .catch( async function (error) {
                // 取得失敗
                me.$buefy.toast.open({
                    message: '保存失敗しました',
                    type: 'is-danger'
                })
            });
        },
        // ダウンロード（単体）
        singleDownload: async function () {
            const me = this;

            if (! this.downloadFileId) {
                this.$buefy.toast.open({
                    message: 'ダウンロードファイルパスを選択してください',
                    type: 'is-danger'
                })
                return;
            }

            await axios.get('/api/download?id=' + this.downloadFileId, {responseType:'blob'})
            .then( async function (response) {

                // 取得成功
                me.fileName = me.getDownloadFileName(response.headers['content-disposition']);
                me.fileValue = new Blob([response.data], {type: response.headers['content-type']});

                const reader = new FileReader();
                reader.readAsDataURL(me.fileValue);

                reader.onload = function(){
                    const url = reader.result;

                    me.fileLink = url;
                }

                me.$buefy.toast.open({
                    message: '取得しました',
                    type: 'is-success'
                })
            })
            .catch( async function (error) {
                // 取得失敗
                me.$buefy.toast.open({
                    message: '取得失敗しました',
                    type: 'is-danger'
                })
            });
        },
        // 削除（単体）
        singleDelete: async function (file) {
            const me = this;

            await axios.delete('/api/delete?id=' + file.id + '&path=' + file.path)
            .then( async function (response) {
                // 取得成功
                me.getList();
                me.$buefy.toast.open({
                    message: '削除しました',
                    type: 'is-success'
                })
            })
            .catch( async function (error) {
                // 取得失敗
                me.$buefy.toast.open({
                    message: '削除に失敗しました',
                    type: 'is-danger'
                })
            });
        },
        // クリア（単体）
        singleClear: function (hasRequiredMessage = true) {
            this.fileValue = null;
            this.fileName = '';
            this.fileLink = '';
            this.downloadFileId = '';
            this.onSingleFileChange;

            if (hasRequiredMessage) {
                this.$buefy.toast.open({
                    message: 'クリアしました',
                    type: 'is-success'
                })
            }
        },
        // アップロード（複数体）
        multipleUpload: async function () {
            const me = this;

            // 選択チェック
            if (! this.fileUploadList.length > 0) {
                this.$buefy.toast.open({
                    message: 'ファイルを選択してください',
                    type: 'is-danger'
                })
                return;
            }

            // リクエストデータ
            const formData = new FormData()
            this.fileUploadList.forEach(function (fileData, index) {
                formData.append('fileList[]', fileData.value, fileData.name)
            });

            await axios.post('/api/upload/list', formData)
            .then( async function (response) {
                // 取得成功
                me.multipleClear(false);
                me.getList();

                me.$buefy.toast.open({
                    message: '保存しました',
                    type: 'is-success'
                })
            })
            .catch( async function (error) {
                // 取得失敗
                me.$buefy.toast.open({
                    message: '保存失敗しました',
                    type: 'is-danger'
                })
            });
        },
        // 削除（複数）
        multipleDelete: async function () {
            const me = this;

            await axios.delete('/api/delete/list')
            .then( async function (response) {
                // 取得成功
                me.getList();
                me.$buefy.toast.open({
                    message: '全削除しました',
                    type: 'is-success'
                })
            })
            .catch( async function (error) {
                // 取得失敗
                me.$buefy.toast.open({
                    message: '全削除に失敗しました',
                    type: 'is-danger'
                })
            });
        },
        // クリア（複数）
        multipleClear: function (hasRequiredMessage = true) {
            this.fileUploadList = [];
            this.onMultipleFileChange;

            if (hasRequiredMessage) {
                this.$buefy.toast.open({
                    message: 'クリアしました',
                    type: 'is-success'
                })
            }
        },
        // アップロード選択したファイルを選択解除
        deleteUploadMultiple: function(index) {
            this.fileUploadList.splice(index, 1);
        },
        // アップロード済みファイル一覧を取得
        getList: async function () {
            const me = this;

            await axios.get('/api/list')
            .then( async function (response) {
                // 取得成功
                me.fileViewList = response.data;
            })
            .catch( async function (error) {
                // 取得失敗
                me.$buefy.toast.open({
                    message: 'データ取得に失敗しました',
                    type: 'is-danger'
                })
            });
        },
        reload: function () {
            location.reload();
        },
        getDownloadFileName: function (str) {
            const filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/; // 正規表現でfilenameを抜き出す
            const matches = filenameRegex.exec(str);
            if (matches != null && matches[1]) {
                const fileName = matches[1].replace(/['"]/g, '');
                return decodeURI(fileName) // 日本語対応
            } else {
                return null;
            }
        },
    },
    computed: {
    },
    mounted: function () {
        this.getList();
    }
}
</script>

<style scoped>
</style>
