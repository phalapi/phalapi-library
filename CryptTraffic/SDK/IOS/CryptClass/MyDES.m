//
//  MyDES.m
//  ZGRDemo
//
//  Created by zhou on 7/6/15.
//  Copyright (c) 2015 zhou. All rights reserved.
//

#import "MyDES.h"
#include <CommonCrypto/CommonCryptor.h>
#import "GTMBase64.h"
#import "MyMD5.h"


#define DES_KEY_EVERYDAY

@implementation MyDES

+(instancetype)shareManager{
    return [[self alloc] initWithBaseURL:nil];
}

+(instancetype)initManager{
    return [[self alloc] init];
}

- (instancetype)init {
    return [self initWithBaseURL:nil];
}

- (void)startWithCompletionBlockWithSuccess:(void (^)(MyDES *myDes))success
                                    failure:(void (^)(MyDES *myDes))failure{
    [self setCompletionBlockWithSuccess:success failure:failure];
    //[self initwithBase];
}

//- (void)initwithBase{
//    GetInfoApi *api = [[GetInfoApi alloc] init];
//    api.baseUrl = API_BASE_URL;
//    api.requestUrl = @"interceptor/keyInterceptor!rKey.action";
//    //    if ([api cacheJson]) {
//    //        NSDictionary *json = [api cacheJson];
//    //        NSLog(@"json = %@", json);
//    //    }
//    [api startWithCompletionBlockWithSuccess:^(YTKBaseRequest *request) {
//        NSLog(@"update ui");
//        if(request.statusCodeValidator){
//            if ([request.responseJSONObject isKindOfClass:[NSDictionary class]]) {
//                if ([[request.responseJSONObject objectForKey:@"rCode"] isEqualToString:@"1"]) {
//                    basekey = [request.responseJSONObject objectForKey:@"rKey"];
//                }else{
//                    basekey = @"";
//                }
//                if (self.successCompletionBlock)
//                {
//                    self.successCompletionBlock(self);
//                }
//
//            }else{
//                basekey = @"";
//            }
//            
//        }
//        //        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@" " message:@"成功" delegate:nil cancelButtonTitle:@"ok" otherButtonTitles: nil];
//        //        [alert show];
//    } failure:^(YTKBaseRequest *request) {
//        //        NSLog(@"failed");
//        //        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@" " message:@"失败" delegate:nil cancelButtonTitle:@"ok" otherButtonTitles: nil];
//        //        [alert show];
//    }];
//}

- (void)setCompletionBlockWithSuccess:(void (^)(MyDES *request))success
                              failure:(void (^)(MyDES *request))failure {
    self.successCompletionBlock = success;
    self.failureCompletionBlock = failure;
}

- (instancetype)initWithBaseURL:(NSURL *)url {
    self = [super init];
    if (!self) {
        return nil;
    }
    baseUrl = [NSString stringWithFormat:@"%@",url];
    basekey = @"";
   //    if ([api cacheJson]) {
//        NSDictionary *json = [api cacheJson];
//        NSLog(@"json = %@", json);
// //        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@" " message:@"成功" delegate:nil cancelButtonTitle:@"ok" otherButtonTitles: nil];
//        [alert show];

    
    return self;
}

/*
-(NSString*) decryptUseDES:(NSString*)cipherText,

{
    // 利用 GTMBase64 解碼 Base64 字串
    NSData* cipherData = [GTMBase64 decodeString:cipherText];
    unsigned char buffer[1024];
    memset(buffer, 0, sizeof(char));
    size_t numBytesDecrypted = 0;
    

    // IV 偏移量不需使用
    CCCryptorStatus cryptStatus = CCCrypt(kCCDecrypt,
                                          kCCAlgorithmDES,
                                          kCCOptionPKCS7Padding | kCCOptionECBMode,
                                          [basekey UTF8String],
                                          kCCKeySizeDES,
                                          nil,
                                          [cipherData bytes],
                                          [cipherData length],
                                          buffer,
                                          1024,
                                          &numBytesDecrypted);
    NSString* plainText = nil;
    if (cryptStatus == kCCSuccess) {
        NSData* data = [NSData dataWithBytes:buffer length:(NSUInteger)numBytesDecrypted];
        plainText = [[NSString alloc] initWithData:data encoding:NSUTF8StringEncoding];
    }
    return plainText;
}



-(NSString *) encryptUseDES:(NSString *)clearText
{
    NSData *data = [clearText dataUsingEncoding:NSUTF8StringEncoding allowLossyConversion:YES];
    unsigned char buffer[1024];
    memset(buffer, 0, sizeof(char));
    size_t numBytesEncrypted = 0;
    CCCryptorStatus cryptStatus = CCCrypt(kCCEncrypt,
                                          kCCAlgorithmDES,
                                          kCCOptionPKCS7Padding | kCCOptionECBMode,
                                          [basekey UTF8String],
                                          kCCKeySizeDES,
                                          nil,
                                          [data bytes],
                                          [data length],
                                          buffer,
                                          1024,
                                          &numBytesEncrypted);
    
    NSString* plainText = nil;
    if (cryptStatus == kCCSuccess) {
        NSData *dataTemp = [NSData dataWithBytes:buffer length:(NSUInteger)numBytesEncrypted];
        plainText = [GTMBase64 stringByEncodingData:dataTemp];
    }else{
        NSLog(@"DES加密失败");
    }
    return plainText;
}*/




-(NSString*) decryptUseDES:(NSString*)cipherText key:(NSString*)key {
    // 利用 GTMBase64 解碼 Base64 字串
    NSData* cipherData = [GTMBase64 decodeString:cipherText];
    int length = (int)cipherData.length;
    int scale = length/1024+1;
    unsigned char buffer[1024*scale];
    memset(buffer, 0, sizeof(char));
    size_t numBytesDecrypted = 0;
    // IV 偏移量不需使用
    //Byte iv[] = {1,2,3,4,5,6,7,8};
    CCCryptorStatus cryptStatus = CCCrypt(kCCDecrypt,
                                          kCCAlgorithmDES,
                                          kCCOptionPKCS7Padding | kCCOptionECBMode,
                                          //kCCOptionPKCS7Padding,
                                           [key UTF8String],
                                           kCCKeySizeDES,
                                          //kCCKeySizeMaxBlowfish,
                                          nil,
                                          [cipherData bytes],
                                          [cipherData length],
                                          buffer,
                                          1024*scale,
                                          &numBytesDecrypted);
    NSString* plainText = nil;
    if (cryptStatus == kCCSuccess) {
        NSData* data = [NSData dataWithBytes:buffer length:(NSUInteger)numBytesDecrypted];
        plainText = [[NSString alloc] initWithData:data encoding:NSUTF8StringEncoding];
    }
    return plainText;
}



-(NSString *) encryptUseDES:(NSString *)clearText key:(NSString *)key
{
    
    //NSLog(@"Base64(1234)%@",[GTMBase64 stringByEncodingData:dataTemp];)
    NSData *data = [clearText dataUsingEncoding:NSUTF8StringEncoding allowLossyConversion:YES];
    int length = (int)data.length;
    int scale = length/1024+1;
    unsigned char buffer[1024*scale];
    memset(buffer, 0, sizeof(char));
    size_t numBytesEncrypted = 0;
    
    //Byte iv[] = {1,2,3,4,5,6,7,8};
    
    CCCryptorStatus cryptStatus = CCCrypt(kCCEncrypt,
                                          kCCAlgorithmDES,
                                          kCCOptionPKCS7Padding | kCCOptionECBMode,
                                          //kCCOptionPKCS7Padding,
                                          [key UTF8String], //key吻合
                                          kCCKeySizeDES,
                                          nil,              //偏移吻合
                                          [data bytes],     //明文
                                          [data length],    //明文长度
                                          buffer,           //传出参数
                                          1024*scale,             //缓冲区长度
                                          &numBytesEncrypted);
    
    NSString* plainText = nil;
    //NSString* plainText2 = nil;
    if (cryptStatus == kCCSuccess) {
//        NSLog(@"buffer=%s",buffer);
        //NSData *testData = [@"123456" dataUsingEncoding: NSUTF8StringEncoding];
        NSData *dataTemp = [NSData dataWithBytes:buffer length:(NSUInteger)numBytesEncrypted ];
        //plainText2 = [GTMBase64 stringByEncodingData:testData ];
        plainText = [GTMBase64 stringByEncodingData:dataTemp ];
    }else{
        NSLog(@"DES加密失败");
    }
    return plainText;
}


@end
